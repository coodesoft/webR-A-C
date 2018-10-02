<?php
use TodoPago\Sdk;

class PedidoTodoPago
{
  public $pedido;
  public $items = [];
  public $envio;
  private $errors = 'Error en medio de pago..';

  const MERCHANTPROD = '4742';
  const AUTHKEYPROD = 'PRISMA 6183707F0EEB829BAC551318382CF919';
  const SECURITYPROD = '6183707F0EEB829BAC551318382CF919';

  const MERCHANTTEST = '2323';
  const AUTHKEYTEST = 'TODOPAGO a414b821c9034137a566d6f1ec077578';
  const SECURITYTEST = 'a414b821c9034137a566d6f1ec077578';

  public $URL_OK  = '';
  public $URL_KO  = '';
  const RutaSdk = '/../commons/extensions/TodoPago/Sdk.php';

  private $merchant;
  private $authKey;
  private $security;

  public $operationId;

  public static $description = [
    'name'           => 'Todo Pago',
    'img'            => '/images/icon-payment-01.png',
    'checkout_notes' => 'Al continuar con la compra, estarás ingresando a un sitio seguro de Todo Pago.',
    'enlacePromo'    => '/images/todo_pago_promo.html',
  ];

  public static function isEnabled(){
    return ConfiguracionesWeb::getConfigWeb()->todo_pago_enabled;
  }

  public static function isEmbebbedPayment(){
    return true;
  }

  public function registrarPedido(){
  }
    
  public function __construct(){

    $mode = Yii::app()->params['mode'];

    if ($mode == 'prod') {
        $this->merchant = self::MERCHANTPROD;
        $this->authKey  = self::AUTHKEYPROD;
        $this->security = self::SECURITYPROD;
    } else if ($mode == 'test') {
        $this->merchant = self::MERCHANTTEST;
        $this->authKey  = self::AUTHKEYTEST;
        $this->security = self::SECURITYTEST;
    }

    $url =  Yii::app()->params[Yii::app()->params['mode']]['URL'];
    $this->URL_OK = $url.'/productos/exitotp?operationid=';
    $this->URL_KO = $url.'productos/failtp??operationid=';
  }

  public static function cargarSdk($http_header){
    $mode = Yii::app()->params['mode'];
    require_once Yii::app()->basePath.self::RutaSdk;
    return new Sdk($http_header, $mode);
  }

  public function cargarPedido(){
    $formData          = $this->prepareForm();
    $this->operationId = $this->pedido->id_pedido; //observado
    $dirFacturacion    = $this->pedido->getDireccionFacturacion();
    $dirEnvio          = $this->pedido->getDireccionEnvio();

    $http_header = [
        'Authorization' => $this->authKey,
        'user_agent'    => 'PHPSoapClient',
    ];

    //opciones para el método sendAuthorizeRequest
    $optionsSAR_comercio = [
        'Security'       => $this->security,
        'EncodingMethod' => 'XML',
        'Merchant'       => $this->merchant,
        'URL_OK'         => $this->URL_OK . $this->operationId,
        'URL_ERROR'      => $this->URL_KO . $this->operationId,
    ];

    $optionsSAR_operacion = [
        'MERCHANT'     => $this->merchant,
        'OPERATIONID'  => $this->operationId,
        'CURRENCYCODE' => 26,
        'AMOUNT'       => $formData['total'],
        //Datos ejemplos CS
        'CSBTCITY'     => $dirFacturacion->ciudad->ciudad, //Ciudad de facturación, MANDATORIO.
        'CSSTCITY'     => $dirEnvio->ciudad->ciudad, //Ciudad de envío de la orden. MANDATORIO.

        'CSBTCOUNTRY'  => "AR", //País de facturación. MANDATORIO. Código ISO. (http://apps.cybersource.com/library/documentation/sbc/quickref/countries_alpha_list.pdf)
        'CSSTCOUNTRY'  => "AR", //País de envío de la orden. MANDATORIO.

        'CSBTEMAIL'     => Yii::app()->user->email, //Mail del usuario al que se le emite la factura. MANDATORIO.
        'CSSTEMAIL'     => Yii::app()->user->email, //Mail del destinatario, MANDATORIO.

        'CSBTFIRSTNAME' => $dirFacturacion->nombre_destinatario, //Nombre del usuario al que se le emite la factura. MANDATORIO.
        'CSSTFIRSTNAME' => $dirEnvio->nombre_destinatario, //Nombre del destinatario. MANDATORIO.

        'CSBTLASTNAME'  => $dirFacturacion->apellido_destinatario, //Apellido del usuario al que se le emite la factura. MANDATORIO.
        'CSSTLASTNAME'  => $dirEnvio->apellido_destinatario, //Apellido del destinatario. MANDATORIO.

        'CSBTPHONENUMBER' => $dirFacturacion->telefono, //Teléfono del usuario al que se le emite la factura. No utilizar guiones, puntos o espacios. Incluir código de país. MANDATORIO.
        'CSSTPHONENUMBER' => $dirEnvio->telefono, //Número de teléfono del destinatario. MANDATORIO.

        'CSBTPOSTALCODE' => $dirFacturacion->cpostal, //Código Postal de la dirección de facturación. MANDATORIO.
        'CSSTPOSTALCODE' => $dirEnvio->cpostal, //Código postal del domicilio de envío. MANDATORIO.

        'CSBTSTATE' => $dirFacturacion->provincia->codigoTP, //Provincia de la dirección de facturación. MANDATORIO. Ver tabla anexa de provincias.
        'CSSTSTATE' => $dirEnvio->provincia->codigoTP, //Provincia de envío. MANDATORIO. Son de 1 caracter

        'CSBTSTREET1' => $dirFacturacion->fullDomicilio, //Domicilio de facturación (calle y nro). MANDATORIO.
        'CSSTSTREET1' => $dirEnvio->fullDomicilio, //Domicilio de envío. MANDATORIO.

        'CSBTCUSTOMERID'       => Yii::app()->user->id, //Identificador del usuario al que se le emite la factura. MANDATORIO. No puede contener un correo electrónico.
        'CSBTIPADDRESS'        => Commons::getUserIP(), //IP de la PC del comprador. MANDATORIO.
        'CSPTCURRENCY'         => "ARS", //Moneda. MANDATORIO.
        'CSPTGRANDTOTALAMOUNT' => self::formatPrice($items['total']), //Con decimales opcional usando el punto como separador de decimales. No se permiten comas, ni como separador de miles ni como separador de decimales. MANDATORIO.

        'CSITPRODUCTCODE'        => $formData['code'], //Código de producto. CONDICIONAL. Valores posibles(adult_content;coupon;default;electronic_good;electronic_software;gift_certificate;handling_only;service;shipping_and_handling;shipping_only;subscription)
        'CSITPRODUCTDESCRIPTION' => $formData['etiquetas'], //Descripción del producto. CONDICIONAL.
        'CSITPRODUCTNAME'        => $formData['etiquetas'], //Nombre del producto. CONDICIONAL.
        'CSITPRODUCTSKU'         => $formData['ids'], //Código identificador del producto. CONDICIONAL.
        'CSITTOTALAMOUNT'        => $formData['unitarios'], //CSITTOTALAMOUNT=CSITUNITPRICE*CSITQUANTITY "999999[.CC]" Con decimales opcional usando el punto como separador de decimales. No se permiten comas, ni como separador de miles ni como separador de decimales. CONDICIONAL.
        'CSITQUANTITY'           => $formData['cantidades'], //Cantidad del producto. CONDICIONAL.
        'CSITUNITPRICE'          => $formData['unitarios'], //Formato Idem CSITTOTALAMOUNT. CONDICIONAL.
    ];

    $tp  = self::cargarSdk($http_header);
    $rta = $tp->sendAuthorizeRequest($optionsSAR_comercio, $optionsSAR_operacion);

    Yii::app()->session['RequestKey'] = $rta['RequestKey']; //?

    if ($rta['StatusCode'] == -1) {
        $this->pedido->urlFormPago = $rta['URL_Request'];
        return true;
    } else {
        return false;
    }
  }

  private static function formatPrice($precio, $decimals = 2)
  {
      return number_format($precio, $decimals, '.', '');
  }

  private function prepareForm()
  {
      $items = (array) $this->items;
      $form = [];
      if ($items !== null) {
          foreach ($items as $item) {
              if ($item->producto) {
                  $form['descripciones'][] = TextHelper::character_limiter($item->producto['descripcion'], 20) ?: 'Descripción del producto no disponible.';
                  //Solo acepta numeros con 2 decimales y seperador decimal en forma de punto ( . )
                  $form['unitarios'][] = number_format($item->precio_unitario,2, '.', '');
                  $form['cantidades'][] = $item->cantidad;
                  $form['etiquetas'][] = $item->producto->etiqueta;
                  $form['ids'][] = $item->producto->categoria->categoria_id.'_'.$item->producto->producto_id;
                  $form['code'][] = 'electronic_good';
              }
          }
          
         if ($this->envio) {
            $form['descripciones'][] = TextHelper::character_limiter($this->envio['descripcion'], 20);
            $form['unitarios'][] =  number_format($this->envio['costo'],2, '.', '');
            $form['cantidades'][] = 1;
            $form['etiquetas'][] = $this->envio['nombre'];
            $form['ids'][] = $this->envio['id'];
            $form['code'][] = 'electronic_good';
            $items['total'] =  number_format($items['total'] + $this->envio['costo'],2, '.', '');
          }

          $form['unitarios'] = implode('#', $form['unitarios']);
          $form['cantidades'] = implode('#', $form['cantidades']);
          $form['etiquetas'] = implode('#', $form['etiquetas']);
          $form['ids'] = implode('#', $form['ids']);
          $form['descripciones'] = implode('#', $form['descripciones']);
          $form['code'] = implode('#', $form['code']);
          $form['total'] = $items['total'];

      }

      return $form;
  }

  public function getErrors(){
    return $this->errors;
  }

  public static function getResponse() // esta esta copiada tal cual
  {
      $tp = new PedidoTodoPago();

      $rk = Yii::app()->session['RequestKey'];
      $ak = $_GET['Answer'];
      $operationid = $_GET['operationid'];
      $optionsGAA = array (
          'Security'   => $tp->security,
          'Merchant'   => $tp->merchant,
          'RequestKey' => $rk,
          'AnswerKey'  => $ak // *Importante
      );
      //común a todas los métodos
      $http_header = ['Authorization'=>$tp->authKey];
      //creo instancia de la clase TodoPago
      $connector = self::cargarSdk($http_header);
      $rta2 = $connector->getAuthorizeAnswer($optionsGAA);

      return $rta2;
  }
}
 ?>
