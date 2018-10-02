<?php
class PedidoMercadoPago
{
  const RutaSdk    = '/extensions/mercadoPago/lib/mercadopago.php';
  const RutaClase  = '/../commons/extensions/MercadoPago.php';
  const TiquetMPOK = 201;

  private $errors = 'Error en medio de pago..';

  public $items = [];
  public $pedido;
  public $urlFormPago;



  public static $description = [
    'name'           => 'Mercado Pago',
    'img'            => '/images/icon-payment-02.png',
    'checkout_notes' => 'En el siguiente paso se abrirá una ventana segura de Mercado Pago en donde podrás completar los detalles del pago.',
    'enlacePromo'    => 'https://www.mercadopago.com.ar/cuotas',
  ];

  public static function isEnabled(){
    return ConfiguracionesWeb::getConfigWeb()->mercado_pago_enabled;
  }

  public static function isEmbebbedPayment(){
    return true;
  }

  // Esta funcion habria que cambiarla a la clase MercadoPago?
  public static function getEstadoCode($k){
    $estados = [
      'approved'     => PagosElectronicos::PAGADO,
      'pending'      => PagosElectronicos::RP_PENDING,
      'in_process'   => PagosElectronicos::IN_PROCESS,
      'rejected'     => PagosElectronicos::REJECTED,
      'refunded'     => PagosElectronicos::REFUNDED,
      'cancelled'    => PagosElectronicos::CANCELLED,
      'in_mediation' => PagosElectronicos::IN_MEDIATION,
      'charged_back' => PagosElectronicos::CHARGED_BACK,
    ];
    return $estados[$k];
  }

  public static function getEstadoOrdenCode($k){
    $estados = [
      'open'    => PedidoOnline::ORDER_OPEN,
      'closed'  => PedidoOnline::ORDER_CLOSED,
      'expired' => PedidoOnline::ORDER_EXPIRED,
    ];
    return $estados[$k];
  }

  private function prepareForm($items)
  {
      $WebConfig = ConfiguracionesWeb::getConfigWeb();
      $form = [];
      if ($items !== null) {
          foreach ($items as $item) {
              if ($item->producto) {
                  $form[] = [
                      "id"          => $item->producto->producto_id,
                      "title"       => $item->producto->etiqueta,
                      "currency_id" => "ARS",
                      "picture_url" => $item->producto->foto,
                      "description" => $item->producto->etiqueta,
                      "category_id" => $item->producto->categoria->etiqueta_web,
                      "quantity"    => (int) $item->cantidad,
                      "unit_price"  => (float) $item->precio_unitario
                  ];
              }
          }
      }

      if ($this->envio) {
        $form[] = [
            "id"          => $this->envio['id'],
            "title"       => $this->envio['nombre'],
            "currency_id" => "ARS",
            "description" => $this->envio['descripcion'],
            "quantity"    => 1,
            "unit_price"  => (float) $this->envio['costo']
        ];
      }
    
      return $form;
  }

  public static function cargarFramework(){
    require_once Yii::app()->basePath.self::RutaClase;
    require_once Yii::app()->basePath.self::RutaSdk;
    //se inicializan los parametros basicos de la API
    if (Yii::app()->params['mode'] == 'prod') {
        return new MP(MercadoPago::CLIENT_ID, MercadoPago::SECRET_ID);
    } else if (Yii::app()->params['mode'] == 'test') {
        return new MP(MercadoPago::CLIENT_ID_TEST, MercadoPago::SECRET_ID_TEST);
    }
   
  }

  public function cargarPedido(){
    $mp = self::cargarFramework();
    $accessToken = $mp->get_access_token();
    $mp->sandbox_mode(MercadoPago::SANDBOX_MODE);

    $mpClass  = new MercadoPago;
    $formData = $this->prepareForm($this->items);

    $user           = User::model()->findByPk(Yii::app()->user->id);
    $dirFacturacion = $this->pedido->getDireccionFacturacion();
    $dirEntrega     = $this->pedido->getDireccionEnvio();

    $preference_data = [
        'items' => $formData,
        'payer' => [
            'name'           => $user->profile->firstname,
            'surname'        => $user->profile->lastname,
            'email'          => $user->email,
            'phone'          => ['area_code' => $dirFacturacion->prefijo,  'number'      => $dirFacturacion->telefono],
            'identification' => ['type'      => $dirFacturacion->dni_tipo, 'number'      => $dirFacturacion->dni],
            'address'        => ['zip_code'  => $dirFacturacion->cpostal,  'street_name' => $dirFacturacion->domicilio,'street_number' => $dirFacturacion->numero,]
        ],
        'shipments' => [
            'mode' => 'not_specified',
            'free_shipping' => true,
            'receiver_address' => [
                'zip_code'     => $dirEntrega->cpostal,
                'street_name'  => $dirEntrega->domicilio,
                'street_number' => $dirEntrega->numero,
                'floor'         => $dirEntrega->piso ? $dirEntrega->piso : '',
                'apartment'     => $dirEntrega->depto ? $dirEntrega->depto : ''
            ],
        ],
        'back_urls'          => ['success' => $mpClass->URL_OK, 'failure' => $mpClass->URL_KO, 'pending' => $mpClass->URL_PEND],
        'notification_url'   => $mpClass->URL_IPN,
        'external_reference' => $this->pedido->id_pedido,
        'auto_return'        => 'all'
    ];

    $preference = $mp->create_preference($preference_data);

    if ($preference['status'] == self::TiquetMPOK) {
      //si el estado es OK preparamos el log
      $preference['response']['items'] = ''; //eliminamos la sección de los items por que seria redundante
      $this->pedido->logOperacion = json_encode($preference['response']); //lo de la tabla de logs ya no va mas, o eso creo

      $this->pedido->urlFormPago = $preference['response'][$mpClass->process_url_key];
      return true;
    } else {
      return false;
    }
  }

  public function getErrors(){
    return $this->errors;
  }
}
 ?>
