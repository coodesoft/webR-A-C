<?php
class MercadoPagoWHData extends MercadoPago{
  private $type;
  private $event;

  public function __construct($params = []){
    if (isset($params['log']))
      $this->log = $params['log'];

    $this->type  = $params['type'];
    $this->event = $params['event'];
  }

  public function procesar(){
    $mp = self::getMPSDK();
    $mp->get_access_token();


     //// PAYMENT
    ///////////////////////////////////////////////////////////////////////////
    if ($this->topic === 'payment'){
      $payment_info = $mp->get('/collections/notifications/'.$this->id);
      $merchantOrderData = $mp->get('/merchant_orders/'.$payment_info["response"]["collection"]["merchant_order_id"]);

      $this->toLog('PI>> '.json_encode($payment_info));
      $this->toLog('MO>> '.json_encode($merchantOrderData));
    }
  }

}
 ?>
