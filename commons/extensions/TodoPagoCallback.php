<?php
class TodoPagoCallback{
  private $idOperacion;
  private $mensaje = '';

  public function __construct($idOperacion){
    $this->idOperacion = $idOperacion;
  }

  public function getMessages(){
    return $this->mensaje;
  }

  public function exito(){
    $rta2 = PedidoTodoPago::getResponse();

    LogData::log('TP>> '.json_encode($rta2));

    $payload = $rta2['Payload'];

    if ($rta2['StatusCode'] == -1) {

        $pedido = PedidoOnline::model()->findByPk($this->idOperacion);
        
        if ($pedido == null) {
           LogData::log('El pedido "'.$this->idOperacion.'" no se pudo encontrar');
           return false;
        }

        $transaction=Yii::app()->db->beginTransaction();

        //Actualizamos pago
        try {
          LogData::log('Se actualiza estado del pago');
          $this->MPActualizarPagoDB($pedido,$payload);
        }
        catch (Exception $e) {
          $transaction->rollBack();
          //ERROR! Deberia ir en log aparte
          LogData::log('Error al actualizar estado del pago. '.$e->getMessage());
          $this->mensaje = 'Error en el proceso. Vuelva a intentar.';
          return false;
        }

        try {
          LogData::log('Se procesa el pedido');
          $pedido->procesar($this->id,PedidoOnline::ORDER_CLOSED);
        }
        catch (Exception $e) {
          $transaction->rollBack();
          //ERROR! Deberia ir en log aparte
          LogData::log('Error al procesar el pedido. '.$e->getMessage());
          $this->mensaje = 'Error en el proceso. Vuelva a intentar.';
          return false;
        }

        $this->mensaje = 'La venta ha sido procesada. ¡Felicidades!';
        $transaction->commit();
        return true;

    } else {
      $this->mensaje = 'Error en el proceso. Vuelva a intentar.';
      return false;
    }
  }

  public function fail(){
    $this->mensaje = 'Error en el proceso. Vuelva a intentar.';
    return false;
  }

  private function MPActualizarPagoDB($pedido,$payment_info){

    $coupon_id = null;
    $estado             = PagosElectronicos::PAGADO;
    $id_servicio_pago   = $payment_info['Answer']['OPERATIONNUMBER'];
    $transaction_amount = $payment_info['Request']['AMOUNTBUYER'];
    $total_paid_amount = $payment_info['Request']['AMOUNT'];

    $pedido->actualizarCrearPago($id_servicio_pago,$estado,$transaction_amount,$total_paid_amount,$coupon_id);

  }
}
 ?>
