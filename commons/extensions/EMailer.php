<?php

Yii::import('commons.extensions.*');
require_once('class.phpmailer.php');
require_once('class.smtp.php');

class EMailer {


    private static $fontSizeGlobal = "24px";
    private static $urlWeb = 'http://rac.geneos.com.ar/web';

    /**
    * Send mail method
    */
    public static function sendMail($emailFrom,$emailTo,$subject,$message, $cleanMessage = false, $backup = false) {

        $mode = Yii::app()->params['mode'];     

        // Datos de la cuenta de correo utilizada para enviar vía SMTP
        $smtpHost = Yii::app()->params[ $mode]['email']['host'];  // Dominio alternativo brindado en el email de alta 
        $smtpUsuario = Yii::app()->params[ $mode]['email']['username'];  // Mi cuenta de correo
        $smtpClave = Yii::app()->params[ $mode]['email']['password']; ;  // Mi contraseña
        $smtpName = Yii::app()->params[ $mode]['email']['name']; 

        $bkActive = Yii::app()->params[ $mode]['email']['bkActive'];  // Dominio 
        if ($emailFrom == null)
          $emailFrom = $smtpUsuario;

        $mail = new PHPMailer(true); // Passing `true` enables exceptions
        try {
          $mail->SMTPDebug = Yii::app()->params[$mode]['email']['debug']; 
          $mail->IsSMTP();
          $mail->SMTPAuth = true;
          $mail->Port = 587; 
          $mail->IsHTML(true); 
          $mail->CharSet = "utf-8";

          $mail->Host = $smtpHost; 
          $mail->Username = $smtpUsuario; 
          $mail->Password = $smtpClave;

          $mail->From = $smtpUsuario; // Email desde donde envío el correo.
          $mail->FromName = $smtpName;
         
          $mail->AddReplyTo($emailFrom); // Esto es para que al recibir el correo y poner Responder, lo haga a la cuenta del visitante. 
          $emailTo = explode(',',$emailTo);
          foreach ($emailTo as  $email) {
            $mail->AddAddress( trim($email) ); 
          }

          $mail->Subject = $subject; // Este es el titulo del email.
          if ($cleanMessage)
            $mensajeHtml = nl2br($message);
          else
            $mensajeHtml = $message;
          $mail->Body = "{$mensajeHtml}"; // Texto del email en formato HTML
          $mail->AltBody = "{$message} \n\n "; // Texto sin formato HTML
          // FIN - VALORES A MODIFICAR //

          $mail->SMTPOptions = array(
              'ssl' => array(
                  'verify_peer' => false,
                  'verify_peer_name' => false,
                  'allow_self_signed' => true
              )
          );

          if ($backup && $bkActive) {
            //ADD BCC TO CONFIGURED MAIL
            $bk_email = Yii::app()->params[ $mode]['email']['bk_email'];
            $mail->addBCC(trim($bk_email));
          }

          $estadoEnvio = $mail->Send(); 

          return $estadoEnvio;

        } catch (Exception $e) {
            LogData::log('Error al enviar mail: '. $mail->ErrorInfo);
            LogData::log($e->getTraceAsString());
        }



    }

    public static function sendContactMail($form){

      $pbody = $form->body. "<br /><br />Nombre y apellido: " . $form->name . "<br />Email: " . $form->email . "<br />Teléfono: " . $form->phone;

      $mode = Yii::app()->params['mode']; 
      $contactoEmail = Yii::app()->params[$mode]['contactoEmail'];

      $mode = Yii::app()->params['mode']; 
      $contactoEmail = Yii::app()->params[$mode]['contactoEmail'];

      $asunto =       'Contacto desde Pagina web!';

      self::sendMail($form->email,$contactoEmail,$asunto,$pbody,true);
    }

    public static function sendContactFromProductMail($form,$producto){

      $pbody = '';
      if ($producto !== null)
          $pbody = 'Consulta por producto: '. $producto->etiqueta.'<br />';
        
      $pbody .= $form->body."<br /><br />Nombre y apellido: " . $form->name . "<br />Email: " . $form->email . "<br />Teléfono: " . $form->phone;

      $mode = Yii::app()->params['mode']; 
      $contactoEmail = Yii::app()->params[$mode]['contactoEmail'];

      $asunto =       'Contacto por producto desde Pagina web!';

      self::sendMail($form->email,$contactoEmail,$asunto,$pbody,true);
    }

    public function noStockMail(){
      $emailTo = $plataformas->email_recibe_notificaciones_ventas;
      $subject = "Alerta de producto sin stock en compra por web";
      $pbody = 'Administrador, <br><br>El siguiente producto fue pedido pero no hay stock:';
      $pbody .= '<br><br>';
      $pbody .= 'Código: ' . $item->producto['codigo'];
      $pbody .= '<br>';
      $pbody .= 'Producto: ' . $item->producto['etiqueta'];
      $pbody .= '<br><br>';
      $pbody .= 'Cliente';
      $pbody .= '<br>';
      $cliente = Clientes::model()->findByAttributes(
          [
              'user_id' => Yii::app()->user->id
          ]
      );
      $pbody .= 'Apellido y nombre: ' . $cliente->apellidoYNombre;
      $pbody .= '<br>';
      $pbody .= 'Teléfono: ' . $direcciones[0]->telefono;
      $pbody .= '<br>';
      $pbody .= 'Email: ' . $cliente->email;
      $pbody .= '<br><br>';
      $pbody .= '<br><br>';

      //$mail->enviarEmail(Yii::app()->params['adminEmail'], $emailTo, $subject, $pbody);

    }

  public static function sendUserActivationMail($activationLink,$emailTo){
      ConfiguracionesWeb::getConfigWeb();
      $mail =       ConfiguracionesWeb::$mailing_conf->mail;
      $telefono =      ConfiguracionesWeb::$mailing_conf->telefono;
      $web =       ConfiguracionesWeb::$mailing_conf->url_web;
      self::$urlWeb = ConfiguracionesWeb::$mailing_conf->url_web;
      $asunto =       'Activa tu cuenta!';

      $pbody = self::buildHeader();
     
      $pbody .= self::buildSubHeaderActivationMail($activationLink);
            
      $pbody .= self::buildFooter($telefono,$mail,$web);
      self::sendMail(null,$emailTo,$asunto,$pbody);
    }

  public static function sendOrderCreatedNotificacion($pedido){
      $config = ConfiguracionesWeb::getConfigWeb();

      $emailTo =  $config->email_recibe_notificaciones_ventas;
      $asunto =       'Pedido desde la web';

      $pbody = 'Se genero un nuevo pedido desde la web<br>';
     
      $pbody .= 'Pedido: ' .$pedido->getNumero().'<br>';
      $pbody .= 'Fecha: ' .$pedido->fecha.'<br>';

      $pbody .= '<br>Productos:<br>';

      foreach ( $pedido->obtenerItemsCarro() as $item) {
        $prod = Productos::getProductInfo($item->categoria_id, $item->producto_id);
        $pbody .= $item->cantidad.' x '.$prod->etiqueta.'<br>';
      }

      $direccion = $pedido->getDireccionEnvio();
      $pbody .= '<br>Datos de envio:<br>';
      if ($direccion == null) {
        $pbody .= 'Destinatario: '.$direccion->fullName.'<br>';
        $pbody .= 'Destino: '.$direccion->ciudad->ciudad.', '.$direccion->provincia->provincia.' - '.$direccion->cpostal.'<br>';
        $pbody .= 'Direccion: '.$direccion->fullDomicilio.'<br>';
      } else {
        $pbody .= 'Sin datos';
      }
      


      $direccion = $pedido->getDireccionFacturacion();
      $pbody .= '<br>Datos de facturacion:<br>';

      if ($direccion == null) {
        $pbody .= 'Destinatario: '.$direccion->fullName.'<br>';
        $pbody .= 'Destino: '.$direccion->ciudad->ciudad.', '.$direccione->provincia->provincia.' - '.$direccione->cpostal.'<br>';
        $pbody .= 'Direccion: '.$direccion->fullDomicilio;
      } else {
        $pbody .= 'Sin datos';
      }

      self::sendMail(null,$emailTo,$asunto,$pbody);
    }

    /**
    * Se envia mail al cliente notificando que su envio fue enviado
    * Se indica link para hacer el tracking del mismo
    * SOLO SE ENVIA ESTE EMAIL CUANDO SE TRANSFORMA EN VENTA UN 
    * PEDIDO CON ENVIO A DOMICILIO
    **/
    public static function sendOrderSended($pedido){

      ConfiguracionesWeb::getConfigWeb();
      $mail =       ConfiguracionesWeb::$mailing_conf->mail;
      $telefono =      ConfiguracionesWeb::$mailing_conf->telefono;
      $web =       ConfiguracionesWeb::$mailing_conf->url_web;
      self::$urlWeb = ConfiguracionesWeb::$mailing_conf->url_web;
      $dias =       ConfiguracionesWeb::$mailing_conf->orderSended_dias;
      $asunto =       ConfiguracionesWeb::$mailing_conf->orderSended_asunto;

      //Solo para envios rapidos
      $horas =       ConfiguracionesWeb::$mailing_conf->orderSendedFast_horas;
      $requisitos =       ConfiguracionesWeb::$mailing_conf->orderSendedFast_requisitos;

      $traking = 'http://tracking.ocasa.com/tracking.aspx?i=18&airbillnumber='.$pedido->codigo_seguimiento;


      $cliente = Clientes::model()->findByAttributes(
          [
              'user_id' => $pedido->id_user
          ]
      );

      $siteName = Yii::app()->name;
      $user = $cliente->apellidoYNombre;

      $pbody = self::buildHeader();
     
      $pbody .= self::buildSubHeaderOrderSended($pedido,$pedido->codigo_seguimiento,$traking,$dias,$horas);

      if ($pedido->forma_envio == PedidoOnline::FORMAENVIO_ENVIO_RAPIDO)
        $pbody .= self::buildObservation('Requisitos:', $requisitos);

      $pbody .= self::buildObservation('Medio de Pago:', $pedido->getPasarelaPago());
      
      $pbody .= self::buildFooter($telefono,$mail,$web);

      $emailTo =  $cliente->email;
      self::sendMail(null,$emailTo,$asunto,$pbody,false,true);
    }

    /**
    * Se envia mail al cliente notificando de que el pedido ya se encuentra pagado
    * Si es retiro por el local, se le informa que ya puede pasar a retirarlo
    * Si es envio a domicilio se le notifica que el pedido se esta preparando.
    **/
    public static function sendPaymentReceived($pedido){

      ConfiguracionesWeb::getConfigWeb();
      $mail =       ConfiguracionesWeb::$mailing_conf->mail;
      $telefono =      ConfiguracionesWeb::$mailing_conf->telefono;
      $web =       ConfiguracionesWeb::$mailing_conf->url_web;
      self::$urlWeb = ConfiguracionesWeb::$mailing_conf->url_web;
     
      $asunto =     ConfiguracionesWeb::$mailing_conf->paymentReceived_asunto;
      $horarios =       ConfiguracionesWeb::$mailing_conf->paymentReceived_horarios;

      $cliente = Clientes::model()->findByAttributes(
          [
              'user_id' => $pedido->id_user
          ]
      );

      $siteName = Yii::app()->name;
      $user = $cliente->apellidoYNombre;

      $pbody = self::buildHeader();
     
      $pbody .= self::buildSubHeaderPaymentReceived($pedido->forma_envio,$pedido);

      $pbody .= self::buildObservation('Medio de Pago:', $pedido->getPasarelaPago());

      $pbody .= self::buildObservation('Forma Envio:', $pedido->getFormaEnvioText());

      if ($pedido->forma_envio == PedidoOnline::FORMAENVIO_RETIROSUCURSAL) {
         $pbody .= self::buildObservation('Horarios:', $horarios );

        
        //Requisitos transferencia
        if ($pedido->id_medio_pago == 3)
          $requisitos = ConfiguracionesWeb::$mailing_conf->paymentReceived_requisitos_transf;
        //Requisitos tarjeta
        else if ($pedido->esTarjetaCredito())
          $requisitos = ConfiguracionesWeb::$mailing_conf->paymentReceived_requisitos_tarjeta;
        
        $pbody .= self::buildObservation('Requisitos:','Retirar por '. $pedido->bodega->direccion.'. '.$requisitos );
      }   
      
      $pbody .= self::buildFooter($telefono,$mail,$web);
      $emailTo =  $cliente->email;
      self::sendMail(null,$emailTo,$asunto,$pbody,false,true);
    }


    /**
    * Se envia mail al cliente notificando de que nos encontramos
    * a la espera del pago.
    * Una vez pagado se procera a preparar el pedido
    **/
    public static function sendWaitingForPayment($pedido){

      ConfiguracionesWeb::getConfigWeb();
      $mail =       ConfiguracionesWeb::$mailing_conf->mail;
      $telefono =      ConfiguracionesWeb::$mailing_conf->telefono;
      $web =       ConfiguracionesWeb::$mailing_conf->url_web;
      self::$urlWeb = ConfiguracionesWeb::$mailing_conf->url_web;
     
      $asunto =     ConfiguracionesWeb::$mailing_conf->waitingPayment_asunto;
      $horas = ConfiguracionesWeb::$mailing_conf->waitingPayment_horas_limite;
      
      $cliente = Clientes::model()->findByAttributes(
          [
              'user_id' => $pedido->id_user
          ]
      );


      $siteName = Yii::app()->name;
      $user = $cliente->apellidoYNombre;

      $pbody = self::buildHeader();

     
      $pbody .= self::buildSubHeaderWaitingPayment($pedido,$horas);
      
      $pbody .= self::buildProductsDetail($pedido);

      $cuentas =  CuentaBanco::getCuentasBancoTransferenciaOnline();
      foreach ($cuentas as $cuenta) {
        $cuentaBancaria = '<div style="display:block;margin-top:10px" >'.$cuenta->renderMailOutput().'</div>';
      }

      //Solo para transferencias
      if ($pedido->id_medio_pago == 3) {
          $pbody .= self::buildObservation('Cuentas disponibles para transferencia o deposito:', $cuentaBancaria);
      }
      
      $pbody .= self::buildObservation('Medio de Pago:',$pedido->getPasarelaPago());
     
      
      $pbody .= self::buildFooter($telefono,$mail,$web);

      $emailTo =  $cliente->email;
      self::sendMail(null,$emailTo,$asunto,$pbody,false,true);
    }

    private static function buildProductsDetail($pedido){
      $items = $pedido->obtenerItemsCarro();
      $html = '
      <!-- Listado productos-->
      <table style=" font-size:'.self::$fontSizeGlobal.';width:100%; border-collapse: collapse;margin-top:30px;">';

      foreach ($items as $item) {
        $prod = Productos::getProductInfo($item->categoria_id, $item->producto_id);
        
        $html .= '
        <tr>
            <td  style="width:12%;padding-top:30px">
            </td>

            <td  style="vertical-align:top;width:25%;border-top:1px solid #ccc;padding-top:30px">
              <img style="max-width: 100%;" src="http://'.self::$urlWeb.'/assets/timthumb.php?src=repository/'.$prod->primeraFotoGaleria().'&w=170&h=163&zc=1'.'" alt="Imagen '.$prod->etiqueta.'" />
            </td>

            <td  style="vertical-align:top;width:35%;border-top:1px solid #ccc;padding-top:30px;padding-bottom:30px">
              <p style="font-size:1em;color:#f1981f;margin:0 0 10px 0;font-weight:bold">'.$prod->etiqueta.'</p>
              <ul style="margin:0;margin-left: 10px; padding: 0 0 0 5px; font-size:0.45em;color:#231f20;font-style:italic">';
              foreach ($prod->campos as $campo){
                if (isset($prod->{"field_".$campo['slug']})){
                  $html .= '<li>'.$campo['nombre'].': '.$prod->{"field_".$campo['slug']}.'</li>';
                }
              };
             $html .= 
             '</ul>
             </td>';
          $html .= '
          <td style="vertical-align:middle;width:28%;padding-top:30px;padding-bottom:30px; text-align: center">
              <div style="min-height:5.1em;height:100%;margin: 0 40px;background-color:#f1981f;">
                <p style="margin:0;padding-top: 0.66em;font-size:1.15em;color:white;font-weight:bold">'.$item->cantidad.' x </p>
                <p style="margin:0;font-size:1.15em;color:white;font-weight:bold">$ '.Commons::formatPrice($item->precio_unitario).'</p>
                <p style="margin:0;font-size:0.65em;color:white;font-weight:bold">c/u</p>
              </div>
          </td>
        </tr>';
      }
      
      if ($pedido->forma_envio == PedidoOnline::FORMAENVIO_ENVIO
          || $pedido->forma_envio == PedidoOnline::FORMAENVIO_ENVIO_RAPIDO) {
         $html .= '
          <tr>
            <td  style="width:12%;padding-top:30px">
            </td>

            <td  style="vertical-align:top;width:25%;border-top:1px solid #ccc;padding-top:30px">
            </td>

            <td  style="vertical-align:middle;width:35%;border-top:1px solid #ccc;padding-top:30px">
              <p style="font-size:1em;color:#f1981f;margin:0 0 10px 0;font-weight:bold">Envio</p>
            </td>
          <td style="vertical-align:middle;width:28%;padding-top:30px; text-align: center">
              <div style="min-height:5.1em;height:100%;margin: 0 40px;background-color:#f1981f;">
                <p style="margin:0;padding-top: 1.66em;font-size:1.15em;color:white;font-weight:bold">$ '.Commons::formatPrice($pedido->costo_envio).'</p>
              </div>
          </td>
        </tr>';

      }

      $html .= '
        <tr>
            <td  style="width:12%;padding-top:30px">
            </td>

            <td  style="vertical-align:top;width:25%;border-top:1px solid #ccc;padding-top:30px">
            </td>

            <td  style="vertical-align:middle;width:35%;border-top:1px solid #ccc;padding-top:30px">
              <p style="font-size:1em;color:#f1981f;margin:0 0 10px 0;font-weight:bold">TOTAL</p>
            </td>
          <td style="vertical-align:middle;width:28%;padding-top:30px; text-align: center">
              <div style="min-height:5.1em;height:100%;margin: 0 40px;background-color:#f1981f;">
                <p style="margin:0;padding-top: 1.66em;font-size:1.15em;color:white;font-weight:bold">$ '.Commons::formatPrice($pedido->getMonto()+$pedido->costo_envio).'</p>
              </div>
          </td>
        </tr>
      </table>';
      
    return $html;
    }

    private static function buildObservation($title,$observation){
      $html = '
      <!-- Observacion -->
      <table style=" font-size:'.self::$fontSizeGlobal.';width:100%; border-collapse: collapse;margin-top:30px;">

        <tr>
          <td  style="width:12%">
          </td>

          <td  style="vertical-align:top;width:60%;border-top:1px solid #ccc;padding-top:30px;color:#59595b;font-size:0.75em">
            <p style="margin:0;font-weight: bold">'.$title.'</p>
            <p style="margin:0;font-size: 0.7em;">'.$observation.'</p>
          </td>

          <td style="width:28%;">
            
          </td>
        </tr>

      </table>
      <!-- Fin Observacion-->
      ';
      return $html;
    }

    private static function buildSubHeaderWaitingPayment($pedido,$horas){
      $html = '
      <!-- Sub Header 1-->
      <table style=" font-size:'.self::$fontSizeGlobal.';width:100%; border-collapse: collapse;">

        <tr >
          <td  style="width:12%"">
          </td>

          <td  style="padding-bottom: 15px;width:60%;color:#59595b;font-size:1em">
            <p style="margin:0;font-weight:bold">RECIBIMOS TU PEDIDO,</p>
            <p style="margin:0;font-weight:300">AHORA AGUARDAMOS TU PAGO</p>
          </td>

          <td style="width:28%; text-align: center">
            <img style="max-width: 100%;" src="http://'.self::$urlWeb.'/themes/rac/images/tilde.png'.'" alt="Rosario al Costo" />
          </td>
        </tr>';
        if ($pedido->id_medio_pago == 3) {
          $html .='<tr >
                    <td  style="width:12%"">
                    </td>

                    <td  style="width:60%;color:#231f20;font-size:1em">
                      <p style="margin:0;font-weight:bold">Dispones de '.$horas.' horas para hacer la transferencia o dep&oacute;sito.</p>
                    </td>

                    <td style="width:28%">
                    </td>

                  </tr>';
        }
        

      $html .= '</table>
      <!-- Fin Sub Header 1-->
      ';
      return $html;
     }

    private static function buildSubHeaderActivationMail($url){
      $html = '
      <!-- Sub Header 1-->
      <table style=" font-size:'.self::$fontSizeGlobal.';width:100%; border-collapse: collapse;">

        <tr >
          <td  style="width:12%"">
          </td>

          <td  style="padding-bottom: 15px;width:60%;color:#59595b;font-size:1em">
            <p style="margin:0;font-weight:bold">¡ESTAS A SOLO UN PASO!</p>
            <p style="margin:0;font-weight:300">ACTIVA TU CUENTA HACIENDO CLICK <a target="_blank" href="'.$url.'">AQUI</a></p>
          </td>

          <td style="width:28%; text-align: center">
            <img style="max-width: 100%;" src="http://'.self::$urlWeb.'/themes/rac/images/tilde.png'.'" alt="Rosario al Costo" />
          </td>
        </tr>';
      $html .= '</table>
      <!-- Fin Sub Header 1-->
      ';
      return $html;
     }

    private static function buildSubHeaderPaymentReceived($formaEnvio,$pedido){
      if ($formaEnvio == PedidoOnline::FORMAENVIO_ENVIO || $formaEnvio == PedidoOnline::FORMAENVIO_ENVIO_RAPIDO) 
        $msg = 'ESTAMOS PREPARANDO TU PEDIDO';
      else
        $msg = 'TE ESPERAMOS EN NUESTRO LOCAL PARA RETIRAR EL PRODUCTO. PEDIDO #'.$pedido->getNumero();

      $html = '
      <!-- Sub Header 2-->
      <table style=" font-size:'.self::$fontSizeGlobal.';width:100%; border-collapse: collapse;">

        <tr >
          <td  style="width:12%">
          </td>

          <td  style="width:60%;color:#231f20;font-size:1em">
            <p style="margin:0;font-weight:bold">¡GRACIAS POR TU COMPRA!</p>
          </td>

          <td style="width:28%">
          </td>

        </tr>

        <tr >
          <td  style="width:12%">
          </td>

          <td  style="padding-bottom: 15px;width:60%;color:#59595b;font-size:1em">
            <p style="margin:0;font-weight:bold">YA RECIBIMOS TU PAGO,</p>
            <p style="margin:0;font-weight:300">'.$msg.'</p>
          </td>

          <td style="width:28%; text-align: center">
            <img style="max-width: 100%;" src="http://'.self::$urlWeb.'/themes/rac/images/tilde.png'.'" alt="Rosario al Costo" />
          </td>
        </tr>

        

      </table>
      <!-- Fin Sub Header 2-->
      ';
      return $html;
    }

    private static function buildSubHeaderOrderSended($pedido,$tracking,$link,$dias,$horas){
      $html = '
      <!-- Sub Header 2-->
      <table style=" font-size:'.self::$fontSizeGlobal.';width:100%; border-collapse: collapse;">

        <tr >
          <td  style="width:12%">
          </td>

          <td  style="width:60%;color:#231f20;font-size:1empx">
            <p style="margin:0;font-weight:bold">¡TU PEDIDO YA ESTA EN CAMINO!</p>
          </td>

          <td style="width:28%">
          </td>

        </tr>
      ';

      if ($pedido->forma_envio == PedidoOnline::FORMAENVIO_ENVIO_RAPIDO)
        $html .='
        <tr >
          <td  style="width:12%">
          </td>

          <td  style="padding-bottom: 15px;width:60%;color:#59595b;font-size:1em">
            <p style="margin:0;font-weight:300">LLEGARÁ A TU DOMICILIO DENTRO DE LAS PROXIMAS '.$horas.' HORAS</p>
          </td>

          <td style="width:28%; text-align: center">
            <img style="max-width: 100%;" src="http://'.self::$urlWeb.'/themes/rac/images/tilde.png'.'" alt="Rosario al Costo" />
          </td>
        </tr>';
      if ($pedido->forma_envio == PedidoOnline::FORMAENVIO_ENVIO)
        $html .='
        <tr >
          <td  style="width:12%">
          </td>

          <td  style="padding-bottom: 15px;width:60%;color:#59595b;font-size:1em">
            <p style="margin:0;font-weight:bold">NUMERO DE TRACKING '.$tracking.', PODES SEGUIR EL ENVÍO HACIENDO CLICK <a target="_blank" href="'.$link.'">AQUI</a> </p>
            <p style="margin:0;font-weight:300">LLEGARÁ A TU DOMICILIO EN '.$dias.' DIAS HABILES</p>
          </td>

          <td style="width:28%; text-align: center">
            <img style="max-width: 100%;" src="http://'.self::$urlWeb.'/themes/rac/images/tilde.png'.'" alt="Rosario al Costo" />
          </td>
        </tr>';

        
      $html .='
      </table>
      <!-- Fin Sub Header 2-->
      ';
      return $html;
    }

    private static function buildHeader(){
      $html = '
      <div style="border-top:32px solid #ba7e64;padding-top: 4px;font-family:Arial;max-width:900px; width:100%; margin: 0 auto; font-size:'.self::$fontSizeGlobal.'">
        <!-- Header -->
        <table style="width:100%; border-collapse: collapse;">

          <tr style="border-top:4px solid #f1981f;padding-bottom: 4px" >
            <td style="text-align:center;padding: 40px 0;">
              <img  style="max-width: 100%;" src="http://'.self::$urlWeb.'/themes/rac/images/header-logo.png'.'" alt="Rosario al Costo" />
            </td>
          </tr>

        </table>
        <!-- Fin Header -->
      ';
      return $html;
    }

    private static function buildFooter($telefono,$mail,$web){
      $html = '
        <!-- Footer -->
        <table style="font-size:'.self::$fontSizeGlobal.';width:100%; border-collapse: collapse;margin-top:40px">

          <tr style="border-top:1px solid #ccc;">
            <td  style="width:12%">
            </td>

            <td style="width:60%;font-size:0.55em;padding-top: 15px;">
              <p style="font-size:0.65empx;color:#f1981f;margin:0;font-weight:bold">SALTA 2220</p>
              <p style="color:#231f20;margin:0;font-weight:bold">LOCAL 3 - ROSARIO</p>
              <p style="color:#231f20;margin:0;">'.$telefono.'</p>
              <p style="color:#231f20;margin:0;"><a style="color:#f1981f;" href="mailto:'.$mail.'">'.$mail.'</a></p>
              <p style="color:#231f20;margin:0;"><a style="color:#f1981f;" target="_blank" href="http://'.$web.'">'.$web.'</a></p>
            </td>

            <td style="width:28%;text-align:center;">
              <img  style="max-width: 100%;" src="http://'.self::$urlWeb.'/themes/rac/images/robot.png'.'" alt="RAC" />
            </td>
          </tr>

        </table>
        <!-- Fin Footer -->
      </div>
      ';
      return $html;
    }

}
