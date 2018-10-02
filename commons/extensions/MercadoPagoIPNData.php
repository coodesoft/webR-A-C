<?php
class MercadoPagoIPNData extends MercadoPago {
  private $topic;
  private $id;
  private $pagosOk = false;
  private $pagosPending = false;
  private $reabrirPedido = false;

  public function __construct($params = []){
    if (isset($params['log']))
      $this->log = $params['log'];

    $this->topic  = $params['topic'];
    $this->id     = $params['id'];
  }

  public function procesar(){
    $mp = self::getMPSDK();

     LogData::log('Proceso IPN');

    //// MERCHANT ORDER
    ///////////////////////////////////////////////////////////////////////////
    if ($this->topic === 'merchant_order'){
      $merchantOrderData = $mp->get('/merchant_orders/'.$this->id); //obtenemos la info
      $this->toLog('MO>> '.json_encode($merchantOrderData));
/*
      if ($merchantOrderData['status'] == 200){ // si salio todo bien vamos a buscar el pedido y actualizar su info
        $id_pedido = $merchantOrderData['response']['external_reference'];
        $pedido    = PedidoOnline::model()->findByPk();

        //Por ahora no hacemos nada en las notificaciones sobre merchant_order. Asumimos que siempre vamos a recibir notifiaciones de pago cuando los mismos se procesan.
      }

      $this->MPActualizarPagosDB($merchantOrderData,0);*/
      return true;
    }

    //// PAYMENT
    ///////////////////////////////////////////////////////////////////////////
    if ($this->topic === 'payment'){
      $payment_info = $mp->get('/collections/notifications/'.$this->id);
      $merchantOrderData = $mp->get('/merchant_orders/'.$payment_info["response"]["collection"]["merchant_order_id"]);

      $this->toLog('PI>> '.json_encode($payment_info));
      $this->toLog('MO>> '.json_encode($merchantOrderData));

      if ($merchantOrderData['status'] == 200){

        $pedido = PedidoOnline::model()->findByPk($merchantOrderData['response']['external_reference']);
        
        if ($pedido == null) {
           $this->toLog('El pedido "'.$merchantOrderData['response']['external_reference'].'" no se pudo encontrar');
           return false;
        }

        $transaction=Yii::app()->db->beginTransaction();
        //Actualizamos pago
        try {
          $this->toLog('Se actualiza estado del pago');
          $this->MPActualizarPagoDB($pedido,$payment_info["response"]["collection"]);
        }
        catch (Exception $e) {
          $transaction->rollBack();
          //ERROR! Deberia ir en log aparte
          $this->toLog('Error al actualizar estado del pago. '.$e->getMessage());
          return false;
        }

        if($this->pagosOk){
          try {
              
              if ( $this->pagosPending )
                $status = PedidoOnline::ORDER_OPEN;
              else
                $status = PedidoOnline::ORDER_CLOSED;

              $this->toLog('Se procesa el pedido a estado: '.$status);

              $pedido->procesar($this->id,$status);

            } catch (Exception $e) {
              $transaction->rollBack();
              //ERROR! Deberia ir en log aparte
              $this->toLog('Error al procesar el pedido. '.$e->getMessage());
              return false;
            }
        }

        if(!$this->pagosOk && $this->reabrirPedido){
          $pedido->reabrir();
        }  

        $transaction->commit();
        return true;
      }
      
    }
  }

  private function MPActualizarPagoDB($pedido,$payment_info){

    $coupon_id = null;
    $estado             = PedidoMercadoPago::getEstadoCode($payment_info['status']);
    $id_servicio_pago   = $payment_info['id'];
    $transaction_amount = $payment_info['transaction_amount'];
    $total_paid_amount = $payment_info['total_paid_amount'];
    
    //TODO: Add extra information
    $extra = null;

    $this->toLog('ID Servicio pago: '.$payment_info['id']);

    $pedido->actualizarCrearPago($id_servicio_pago,$estado,$transaction_amount,$total_paid_amount,$coupon_id,$extra);

    if($estado == PagosElectronicos::PAGADO || $estado == PagosElectronicos::RP_PENDING){
        $this->pagosOk = true;
    } else {
      $this->pagosOk = false;
    }

    //Controlo si es pending para poder procesar el pedido como ABIERTO
    if ($estado == PagosElectronicos::RP_PENDING)
      $this->pagosPending = true;
    else
      $this->pagosPending = false;

    //TODO: Aca verificar notificacion de cuando el pago expiro, es decir paso el tiempo de pago.
    if($estado == PagosElectronicos::REJECTED || $estado == PagosElectronicos::CHARGED_BACK){
      $this->reabrirPedido = true;
    }

  }


  private function MPActualizarPagosDB($merchantOrderData,$coupon_id){
    foreach ($merchantOrderData['response']['payments'] as $v) {
      //comprobamos si el pago existe para no duplicar registros y actualizar el estado
      $criteria            = new CDbCriteria;
      $criteria->condition = 'id_servicio_pago = '.$v['id'];
      $RPago               = PagosElectronicos::model()->find($criteria);

      if ($RPago == null) {
          $RPago                   = new PagosElectronicos;
          $RPago->id_servicio_pago = $v['id'];
      }

      $RPago->id_pedido          = $merchantOrderData['response']['external_reference'];
      $RPago->coupon_id          = $coupon_id;
      $RPago->estado             = PedidoMercadoPago::getEstadoCode($v['status']);
      $RPago->transaction_amount = $v['transaction_amount'];
      $RPago->total_paid_amount  = $v['total_paid_amount'];
      $RPago->save(false);

      
    }
  }
}
 ?>
