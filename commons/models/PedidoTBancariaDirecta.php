<?php
class PedidoTBancariaDirecta
{

  private $error;

  public static function pendingMessage(){
  	return "Su pedido se encuenta en estado Pendiente de pago. El mismo sera acreditado cuando notifique la transferencia";
  }

  public $pedido;
	
  public static $description = [
    'name'    => 'Transferencia Bancaria o Depósito',
    'img'     => '/images/icon-payment-06.png',
  ];

  public static function isEnabled(){
    return true;
  }

  public static function isEmbebbedPayment(){
    return false;
  }

  public function cargarPedido(){

    $mp = new MercadoPago();
  	$this->pedido->logOperacion = '';

    $this->pedido->urlFormPago = $mp->URL_PEND;

    //Proceso pedido en el instante que se paga con transferencia
    $transaction=Yii::app()->db->beginTransaction();

    try {
        $estado             = PagosElectronicos::PENDIENTE;
        $id_servicio_pago   = 0;
        $transaction_amount = $this->pedido->getMonto()+$this->pedido->costo_envio;
        $total_paid_amount = $this->pedido->getMonto()+$this->pedido->costo_envio;

        $this->pedido->actualizarCrearPago($id_servicio_pago,$estado,$transaction_amount,$total_paid_amount,$coupon_id);
      } catch (Exception $e) {
        $transaction->rollBack();
        //ERROR! Deberia ir en log aparte
        LogData::log('Error al procesar el pedido. '.$e->getMessage());
        $error = $e->getMessage();
        return false;
      }

      try {
        LogData::log('Se procesa el pedido');
        $this->pedido->procesar($this->id,PedidoOnline::ORDER_OPEN);
      } catch (Exception $e) {
        $transaction->rollBack();
        //ERROR! Deberia ir en log aparte
        $error = $e->getMessage();
        LogData::log('Error al procesar el pedido. '.$e->getMessage());
        return false;
      }
      
      $transaction->commit();
    	return true;
  }

  public function getErrors(){
    return $error;
  }
}
 ?>