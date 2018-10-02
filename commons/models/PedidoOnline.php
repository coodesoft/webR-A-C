<?php
  class PedidoOnline extends CActiveRecord{
    //los presentes estados tienen que ver con el estado general de la venta electrónica
    const ORDER_OPEN    = 0; // El pedido no tiene pagos asignados por el total
    const ORDER_CLOSED  = 1; // El pedido tiene montos asignados por el total, pero aun no fueron computados,
    const ORDER_EXPIRED = 2; // este estado se tiene en cuenta cuando un pago rebota o no se efectua? cupones de pago
    const ENTREGADO     = 3; // La orden fue procesada por el TO
    const BORRADOR      = 4; //corresponde a los pedidos que no llegaron al form de pago
    const CANCELADO     = 5; //La orden fue cancelada

    const WARNING_VERAZ     = 2;
    const WARNING_PROCESAR     = 1;
    const WARNING_TIMEDOUT     = 0;

    const FORMAENVIO_ENVIO = 1;
    const FORMAENVIO_RETIROSUCURSAL = 2;
    const FORMAENVIO_ENVIO_RAPIDO = 3;

    const COSTO_FORMAENVIO_ENVIO_RAPIDO = 50;

    public static $FormasPagosOnline = [
      '3'  => PedidoTBancariaDirecta,
      '2'  => PedidoTodoPago,
      '1'  => PedidoMercadoPago,
    ];

    public function getPasarelaPago(){
      switch ($this->id_medio_pago) {
        case 1:
            return 'Mercado pago';
            break;
        case 2:
            return 'Todo Pago';
            break;
        case 3:
            return 'Transferencia Bancaria o Depósito';
            break;
      }
    }

    public function getListaPrecioID(){
      $config = ConfiguracionesWeb::getConfigWeb();
      switch ($this->id_medio_pago) {
        case 1:
            return ProductosPrecios::$PRECIO_MERCADOPAGO_ID;
            break;
        case 2:
            return ProductosPrecios::$PRECIO_TODOPAGO_ID;
            break;
        case 3:
            return ProductosPrecios::$PRECIO_TRANSFERENCIA_ID;
            break;
      }
    }

    public function actualizarListaPrecios(){
      $items = $this->obtenerItemsCarro();
      foreach($items as $item){
        $itemInfo = Productos::getProductInfo($item->categoria_id, $item->producto_id);

        $item->id_precio = $this->getListaPrecioID();

        $item->precio_unitario = Commons::formatPrice($itemInfo->precio[$item->id_precio]['precio'],0,'');

         LogData::log('precio id:'.$this->getListaPrecioID());
        
        $item->precio_total = $item->cantidad * $item->precio_unitario;

        LogData::log('Actualiando precio, Precio unitario: '.$item->precio_unitario.'.precio_total: '.$item->precio_total );


        if (!$item->save())
          throw new Exception('Pedido: '.$this->getNumero().'. Error actualiar precios segun lista de precios: '.Commons::renderError($item->getErrors()));
      }
    }

    private static function isEnabledFormaPagoOnline($medioPago){
      if (isset(self::$FormasPagosOnline[$medioPago])){
        $fp = self::$FormasPagosOnline[$medioPago];
        return $fp::isEnabled();
      } else {
        return false;
      }
    }

    public $urlFormPago;
    public $logOperacion;
    public $carroAbierto = null;

    private static $DireccionFacturacion;
    private static $DireccionEnvio;

    public function cargarPedido(){
      //TODO: Transaccionar!

      //verificar stock
      $ret = CarritoProductosWeb::inStock();

      if (sizeof($ret) > 0) {
        /*
        $mail = new EMailer();
        $mail->noStockMail();
        */
        return ['success' => false, 'error' => 'no_stock', 'msg' => 'No hay stock suficiente','no_stock_items'=>$ret];

      }

      //verificar direcciones de envio
      if ($this->getDireccionEnvio() == null && $this->forma_envio != self::FORMAENVIO_RETIROSUCURSAL){
        return ['success' => false, 'msg' => 'Error: es necesario completar las direcciones de envío'];
      }

      //verificar direccion de facturacion
      if ($this->getDireccionFacturacion() == null && $this->forma_envio != self::FORMAENVIO_RETIROSUCURSAL){
        return ['success' => false, 'msg' => 'Error: es necesario completar las direcciones de facturación'];
      }

      //verificar que el medio de pago ingresado existe o que este habilitado
      if (!self::isEnabledFormaPagoOnline($this->id_medio_pago)){
        return ['success' => false, 'msg' => 'Error: ... en medios de pago'];
      }

      //verifico que si el medio de envio es Envio Rapido entonces la direccion sea Rosario
      if ($this->forma_envio == self::FORMAENVIO_ENVIO_RAPIDO && $this->getDireccionEnvio()->ciudad_id != 9978){
        return ['success' => false, 'msg' => 'Error: el Envio Rapido solo esta disponible para Rosario'];
      }

      //comprobamos que no haya ningun pedido en estado borrador, de ser asi lo
      //sobre escribimos para no duplicar los pedidos (Agrego filtro por usuario)
      $Pedido = $this;
      $criteria = new CDbCriteria;
      $criteria->condition = 'estado = "'.self::BORRADOR.'" and id_user = '.Yii::app()->user->id;
      $BPedido = self::model()->find($criteria);
      if ($BPedido != null){
        //Mantengo el medio de pago del nuevo pedido
        $BPedido->id_medio_pago = $Pedido->id_medio_pago;
        //Mantengo la forma de envio del nuevo pedido
        $BPedido->forma_envio = $Pedido->forma_envio;
        //Mantengo la bodega de retiro del nuevo pedido
        $BPedido->bodega_id = $Pedido->bodega_id;
        $Pedido = $BPedido;
      }

      //completamos los datos del pedido
      $Pedido->id_user               = Yii::app()->user->id;
      $Pedido->estado                = self::BORRADOR;
      $Pedido->direccion_envio       = $this->getDireccionEnvio()->cliente_direccion_id;
      $Pedido->direccion_facturacion = $this->getDireccionFacturacion()->cliente_direccion_id;
      $Pedido->fecha                 = date('Y-m-d H:i:s');

      //calculo costo de envio en caso de ser necesario
      if ($Pedido->forma_envio == self::FORMAENVIO_ENVIO)
        $Pedido->costo_envio = Commons::formatPrice(CarritoProductosWeb::calcularCostoEnvio());
      else if ($Pedido->forma_envio == self::FORMAENVIO_ENVIO_RAPIDO)
        $Pedido->costo_envio = self::COSTO_FORMAENVIO_ENVIO_RAPIDO;
      else
        $Pedido->costo_envio = 0;

      if(!$Pedido->save()) //si aca no podemos guardar mejor no intentar crear el tiquet del mp
        return ['success' => false, 'msg' => 'Error inesperado'];      

      //Guardo ID del pedido en session (Para procesar items en IPN MP)
      Yii::app()->session['pedido_id'] = $Pedido->id_pedido;

      //ya que generamos el registro del pedido vamos a hacer el pedido a la api correspondiente
      $PedidoFP         = new self::$FormasPagosOnline[$this->id_medio_pago];
      //Pasamos el medio de pago para obtener los items con el precio correspondiente, pero sin actualizarlos en la base. Se actualizan en la base al procesar los items
      $PedidoFP->items  = CarritoProductosWeb::getCartItems($Pedido->getListaPrecioID());

      //Agrego Costo Envio
      if ($Pedido->forma_envio == self::FORMAENVIO_ENVIO) {
        $PedidoFP->envio = ['id' => 100,
                            'nombre' => 'Envio a domicilio',
                            'descripcion' => 'Envio a domicilio por OCASA',
                            'costo' =>  floatval($Pedido->costo_envio)];
      }

      if ($Pedido->forma_envio == self::FORMAENVIO_ENVIO_RAPIDO) {
        $PedidoFP->envio = ['id' => 100,
                            'nombre' => 'Envio a domicilio',
                            'descripcion' => 'Envio a domicilio RAPIDO',
                            'costo' =>  floatval(self::COSTO_FORMAENVIO_ENVIO_RAPIDO)];
      }
      
      $PedidoFP->pedido = $Pedido;

      // Las transferencias no necesitan redireccionar a una URL de pago
      if($PedidoFP->cargarPedido()){
        //todo ok, guardamos el log del pedido
        $Pedido->guardarLog($Pedido->logOperacion);
        $this->urlFormPago = $Pedido->urlFormPago;
        return ['success' => true];
      } else {
        return ['success' => false, 'msg' => $PedidoFP->getErrors()];
      }
    }

    public function getFormaPago(){
      return self::$FormasPagosOnline[$this->id_medio_pago];
    } 

    private function guardarLog($log){
      if ($log != null){
        $Reg_log = new LogPedidoOnline;
        $Reg_log->id_pedido = $this->id_pedido;
        $Reg_log->log       = $log;
        $Reg_log->save();
      }
    }

    public function getDireccionEnvio(){
      if(self::$DireccionEnvio == null){
        self::$DireccionEnvio = ClientesDirecciones::findBy($this->direccion_envio);
      }
      return self::$DireccionEnvio;
    }

    public function getDireccionFacturacion(){
      if(self::$DireccionFacturacion == null){
        self::$DireccionFacturacion = ClientesDirecciones::findBy($this->direccion_facturacion);
      }
      return self::$DireccionFacturacion;
    }

    public static function getFormasPagoOnline(){
      return $this::$FormasPagosOnline;
    }

    public static function getFormasPagoData($claves=[]){
      $salida = [];

      foreach (self::$FormasPagosOnline as $k => $fp) {
        if (self::isEnabledFormaPagoOnline($k)){
          if ($claves == []){ // se entrega toda la descripcion
            $salida[$k] = $fp::$description;
          } else { // se entregan solo las claves solicitadas
            foreach ($claves as $fk) {
              $salida[$k][$fk] = $fp::$description[$fk];
            }
          }
        }
      }

      return $salida;
    }

    public static function getFormasEnvioData(){
      $salida = [ self::FORMAENVIO_ENVIO => ['name' => 'Envio a domicilio',
                                            'img'   => '/images/delivery.png'],
                  self::FORMAENVIO_RETIROSUCURSAL => ['name' => 'Retiro por sucursal',
                                                      'img'   => '/images/sucursal.png'],
                  self::FORMAENVIO_ENVIO_RAPIDO => ['name' => 'Envio rapido (Solo Rosario)',
                                                      'img'   => '/images/express_delivery.png']
                ];

      return $salida;
    }

    public static function getBodegasRetiroData(){
      $bodegas = Bodegas::getBodegasRetiroSucursal();
      foreach ( $bodegas as $bodega) {
         $salida[$bodega->bodega_id] = ['name' => $bodega->nombre, 
                                        'checkout_notes' => $bodega->direccion];
      }
      return $salida;
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'pedido_online';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['id_user, fecha, estado, direccion_envio, direccion_facturacion', 'required'],
            ['id_user, estado, direccion_envio, direccion_facturacion', 'numerical', 'integerOnly'=>true],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['id_pedido, id_user, id_mercado_pago, id_todo_pago, fecha, estado', 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
      return array(
        'user' => array(self::HAS_ONE, 'User', 'id_user'),
        'bodega' => array(self::BELONGS_TO, 'Bodegas', 'bodega_id'),
      );
    }

    /**
     * Función para formatear las fechas.
     * Todo lo que sea de tipo date y datetime serán formateados para ser legibles y antes de ser validados para almacanarse
     */
    public function behaviors(){
        return [
            'PFechas' => [
                'class' => 'ext.fechas.PFechas',
            ],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id_pedido'       => 'Id Pedido',
            'id_user'         => 'Id User',
            'id_mercado_pago' => 'Id Mercado Pago',
            'id_todo_pago'    => 'Id Todo Pago',
            'fecha'           => 'Fecha',
            'estado'          => 'Estado',
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id_pedido',$this->id_pedido);
        $criteria->compare('id_user',$this->id_user);
        $criteria->compare('id_mercado_pago',$this->id_mercado_pago);
        $criteria->compare('id_todo_pago',$this->id_todo_pago);
        $criteria->compare('fecha',$this->fecha,true);
        $criteria->compare('estado',$this->estado);

        return new CActiveDataProvider($this, [
            'pagination' => [
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ],
            'criteria'=>$criteria,
        ]);
    }

    /*
    Este metodo genera todas las VentasProductos tomando la informacion del carrito asociado al pedido, con los productos y las cantidades correspondientes.
    */
    public function generarVentasProductos() {

        $cartItems = $this->obtenerItemsCarro();

        $i = 0;
        foreach ($cartItems as $item) {
            $vP = new VentasProductos();
            $vP->producto_id = $item->producto_id;
            $vP->categoria_id = $item->categoria_id;
            $vP->cantidad = $item->cantidad;
            $vP->precio_unitario = $item->precio_unitario;
            $vP->precio_total =   $item->precio_total;
            $vP->precio_id =$item->id_precio;
            $ventasProducto[$i] = $vP;
            $i++;
        }

        return $ventasProducto;
    }

    public function generarPaymentMethods() {

      $pagosElectronicos = $this->obtenerPagosElectronicos();

      if ($this->id_medio_pago != 3)
        foreach ($pagosElectronicos as $pago) {
          $paymentMethod[] = ["forma_pago_id"=>Pago::ONLINE,
                            "monto"=> $pago->total_paid_amount,
                            "nro_comprobante"=> $pago->id_servicio_pago,
                            "fecha"=>$pago->pedido->fecha,
                            //TODO: obtener cuotas de pago electronico
                            "cuotas"=> 0,
                            "source"=> $this->id_medio_pago ];
        }
      //Transferencia
      else 
        foreach ($pagosElectronicos as $pago) {
           $paymentMethod[] = ["forma_pago_id"=>Pago::TRANSFERENCIA,
                            "monto"=> $pago->total_paid_amount,
                            "fecha"=>$pago->transferencia->fecha,
                            "cuenta_banco_id"=> $pago->transferencia->cuenta_banco_id,
                            "nro_comprobante"=> $pago->transferencia->numero ];
        }
      return $paymentMethod ;

    }

    /*
    Este metodo genera una venta acorde a la informacion del pedido
    */
    public function generarVenta() {

        $venta = new Ventas();

        // TODO: La bodega debe venir del pedido online
        $venta->bodega_id = Bodegas::getBodegaOnline();
        $venta->tipo_venta_id = Ventas::TIPO_VENTA_ONLINE;
        $venta->cliente_id = Clientes::obtenerClientePorUserID($this->id_user)->cliente_id;

        //Se crea con la fecha actual
        $venta->fecha = date('Y-m-d H:i:s');
        $venta->total = $this->getMonto();
        $venta->estado_venta_id = Ventas::PAGADO;

        return $venta;
    }

    public function obtenerItemsCarro(){
      //buscamos los productos correspondientes al carro de la web
      $criteria = new CDbCriteria;
      $criteria->condition = 'id_pedido = '.$this->id_pedido;
      $registrosCarro = CarritoProductosWeb::model()->findAll($criteria);

      return $registrosCarro;
    }

    public function obtenerPagosElectronicos(){
      //buscamos los pagos electronicos correspondientes al pedido
      $criteria = new CDbCriteria;
      $criteria->condition = 'id_pedido = '.$this->id_pedido;
      $registrosCarro = PagosElectronicos::model()->findAll($criteria);

      return $registrosCarro;
    }

     public function obtenerIDPrimerPagoElectronico(){
      //buscamos los pagos electronicos correspondientes al pedido
      $criteria = new CDbCriteria;
      $criteria->condition = 'id_pedido = '.$this->id_pedido;
      $registrosCarro = PagosElectronicos::model()->findAll($criteria);

      if (sizeof($registrosCarro) > 0)
        return $registrosCarro[0]->id;
      return 0;
    }

    //En realidad no se obtienen los pagados, sino que se obtienen todos los pedidos que estan pagos o pendientes de pago
    public static function obtenerPagados(){
      $criteria = new CDbCriteria;
      $criteria->condition = 'estado = '.self::ORDER_OPEN.' OR estado = '.self::ORDER_CLOSED;
      //Ademas de los pagados agrego los pendientes de pagar con transferencia
      //.' || (estado = '.self::ORDER_OPEN.' AND id_medio_pago = 3)';
      return PedidoOnline::model()->findAll($criteria);
    }

    public static function obtenerEntregados(){
      $criteria = new CDbCriteria;
      $criteria->condition = 'estado = '.self::ENTREGADO;
      return PedidoOnline::model()->findAll($criteria);
    }

    public static function obtenerExpirados(){
      $criteria = new CDbCriteria;
      $criteria->condition = 'estado = '.self::ORDER_EXPIRED;
      return PedidoOnline::model()->findAll($criteria);
    }

    public static function obtenerCancelados(){
      $criteria = new CDbCriteria;
      $criteria->condition = 'estado = '.self::CANCELADO;
      return PedidoOnline::model()->findAll($criteria);
    }

    public static function obtenerExpiradosCancelados(){
      $criteria = new CDbCriteria;
      $criteria->condition = 'estado = '.self::ORDER_EXPIRED.' OR estado = '.self::CANCELADO;
      return PedidoOnline::model()->findAll($criteria);
    }

    public function obtenerVenta(){
      $criteria = new CDbCriteria;
      $criteria->condition = 'pedido_id = '.$this->id_pedido;
      $ret = Ventas::model()->findAll($criteria);
      if (sizeof($ret) > 0)
        return $ret[0];
      else
        return null;
    }

    public function getNumero(){
        return str_pad($this->id_pedido, 6, 0, STR_PAD_LEFT);
    }

    public function getCliente(){
      return Clientes::obtenerClientePorUserID($this->id_user)->getApellidoYNombre();
    }

    //Calculo monto en base a total de lineas
    public function getMonto(){

      $var_sum = CarritoProductosWeb::model()->findBySql('select ROUND(sum(`precio_total`), 2) as `precio_total` from carrito_productos_web 
        WHERE id_pedido ='.$this->id_pedido, array());

      return $var_sum->precio_total;
    }

    public function getFormaEnvioText(){
      if ($this->forma_envio == self::FORMAENVIO_ENVIO)
        return 'Envio por OCASA';
      if ($this->forma_envio == self::FORMAENVIO_RETIROSUCURSAL)
        return 'Retiro por sucursal ('.$this->bodega->nombre.')';
      if ($this->forma_envio == self::FORMAENVIO_ENVIO_RAPIDO)
        return 'Envio RAPIDO';
    }

    //El estado sera pagado si todos los pagos fueron acreditados, caso contrario sera pendiente
    public function getEstadoPagos(){
      $pagos = $this->obtenerPagosElectronicos();
      foreach ($pagos as $pago) {
        if ($pago->estado != PagosElectronicos::PAGADO)
          return PagosElectronicos::PENDIENTE;
      }

      return PagosElectronicos::PAGADO;
    }

    public function getEstadoPagosText(){
      switch ($this->getEstadoPagos()) {
        case PagosElectronicos::PAGADO:
            return "Pagado";
            break;
        case PagosElectronicos::PENDIENTE:
            return "Pendiente";
            break;
        default:
            return "Desconocido";
            break;
      }
    }

    public function btnGenerarComprobanteOCASA(){
      if ($this->estado == self::ENTREGADO){
          return CHtml::link('<i class="fa fa-edit"></i>',
          ["#"],
          ["rel"=>"tooltip","data-id_pedido"=>$this->id_pedido,"title"=>"Generar comprobante","class"=>"generar-comprobante"]);
      }
    }

    public function btnConvertirEnVenta(){
      if ($this->estado == self::ORDER_CLOSED && $this->getEstadoPagos() == PagosElectronicos::PAGADO){
          return CHtml::link('<i class="fa fa-sign-out"></i>',
          ["PedidoOnline/convertirEnVenta/".$this->id_pedido],
          ["rel"=>"tooltip","title"=>"Procesar Pedido","class"=>""]);
      }
    }

    public function btnCargarTransferencia(){
      if ($this->estado == self::ORDER_OPEN && $this->getEstadoPagos() == PagosElectronicos::PENDIENTE && !$this->isEmbebbedPayment()){
        return CHtml::link('<i class="fa fa-pencil-square-o"></i>',
          ["#"],
          ["rel"=>"tooltip","data-id_pedido"=>$this->id_pedido,"data-id_pago_electronico"=>$this->obtenerIDPrimerPagoElectronico(),"title"=>"Cargar Transferencia","class"=>"load-transferencia"]);
      }
    }

    public function btnExpirarTransferencia(){
      if ($this->estado == self::ORDER_OPEN && $this->getEstadoPagos() == PagosElectronicos::PENDIENTE && !$this->isEmbebbedPayment()){
        return CHtml::link('<i class="fa fa-calendar-times-o"></i>',
          ["#".$this->id_pedido],
          ["rel"=>"tooltip","data-id_pedido"=>$this->id_pedido,"data-id_pago_electronico"=>$this->obtenerIDPrimerPagoElectronico(),"title"=>"Expirar transferencia","class"=>"expirar-transferencia"]);
      }
    }

    public function btnExpirarPedido(){
      if ($this->estado == self::ORDER_OPEN && $this->getEstadoPagos() == PagosElectronicos::PENDIENTE && $this->isEmbebbedPayment()){
        return CHtml::link('<i class="fa fa-calendar-times-o"></i>',
          ["#".$this->id_pedido],
          ["rel"=>"tooltip","data-id_pedido"=>$this->id_pedido,"title"=>"Expirar Pedido","class"=>"expirar-pedido"]);
      }
    }

    public function btnCancelarPedido(){
      if ($this->estado == self::ORDER_CLOSED && $this->getEstadoPagos() == PagosElectronicos::PAGADO && $this->isEmbebbedPayment()){
        return CHtml::link('<i class="fa fa-calendar-times-o"></i>',
          ["#".$this->id_pedido],
          ["rel"=>"tooltip","data-id_pedido"=>$this->id_pedido,"title"=>"Cancelar Pedido","class"=>"cancelar-pedido"]);
      }
    }

    public function btnVerVenta(){
      if ($this->estado == self::ENTREGADO){
        $venta = $this->obtenerVenta();
        return CHtml::link($venta->referencia,
          ["Ventas/view/".$venta->venta_id],
          ["rel"=>"tooltip","title"=>"Ver venta","class"=>""]);
      }
    }

    /*
      Al procesar un pedido:
        - Se procesan sus items del carrito (Se asignan a este pedido y no aparecen mas en la web) -> Esto tambien se hacen en el ok de mercado pago, por fix para que no sigan apareciendo.
        - Se reserva el stock 
        - Se actualiza estado del pedido a CLOSED (Se pago en su totalidad) o OPEN (Aun tiene pagos pendientes).
    */
    public function procesar($id_externo,$estado) {

        if ($this->estado != self::BORRADOR && $this->estado != self::ORDER_OPEN){
          // en la reserva de stock comprobar
          throw new Exception('Pedido: '.$this->getNumero().'.No se puede procesar un pedido que no este en borrador o abierto.');
        }

        if ($this->estado == self::BORRADOR) {
          $this->id_externo   = $id_externo;

          //marcamos los items como procesados
          CarritoProductosWeb::setProcesado($this->id_user,$this->id_pedido);
          unset(Yii::app()->session['pedido_id']);

          $this->reservarStock();

          $this->actualizarListaPrecios();

          //Envio mail de notificacion a administradores
          EMailer::sendOrderCreatedNotificacion($this);
        }

        //Para online NO Efectivo Deberia ser siempre CLOSED (Se paga siempre por el total)
        //Para online Efectivo queda en OPEN hasta que el usuario hace el pago
        //Para tranferencias queda en OPEN hasta procesar transferencia desde TO
        $this->estado       = $estado;

        if (!$this->save())
          throw new Exception('Pedido: '.$this->getNumero().'. Error al procesar pedido: '.Commons::renderError($this->getErrors()));

        //Si el estado destino es CLOSED entonces envio mail de pago recibido
        if ( $estado == self::ORDER_CLOSED) {
          if ($this->getEstadoPagos() == PagosElectronicos::PAGADO){
            LogData::log('Enviando mail de pago recibido...');
            EMailer::sendPaymentReceived($this);
          }
          else
            throw new Exception('Pedido: '.$this->getNumero().'. Error al procesar pedido a estado cerrado: Existen pagos asociados al pedido en estado pendiente');
        }
        else if ( $estado == self::ORDER_OPEN ) {
          LogData::log('Enviando mail de esperando pago...');
          EMailer::sendWaitingForPayment($this);
        }
    }


    /*
      Al reabrir un pedido:
        - Se liberan sus articulos para que queden disponibles en el carrito (POR AHORA NO, NO SE DEVUELVEN AL CARRITO)
        - Se liberan los reservados asociados 
          - Se eliminan las transacciones asociadas a los movimientos de reserva
        - Se cambia estado del pedido a "Borrador"
    */
    public function reabrir() {
      if ( !($this->estado == self::ORDER_CLOSED || $this->estado == self::ORDER_OPEN) ){
          throw new Exception('Pedido: '.$this->getNumero().'.No se puede reabrir un pedido que no este en estado cerrado o abierto.');
        }

        $this->estado       = self::ORDER_EXPIRED;

        //Desreservamos stock
        $this->desreservarStock();

        if (!$this->save())
          throw new Exception('Pedido: '.$this->getNumero().'. Error al procesar pedido: '.Commons::renderError($pedido->getErrors()));
    }

    public function cancelar() {
      //LLamo al reabrir que por ahora es lo mismo
      $this->reabrir();

      //Lo unico que cambia es que seteo el estado a cancelado
      $this->estado       = self::CANCELADO;
      if (!$this->save())
          throw new Exception('Pedido: '.$this->getNumero().'. Error al procesar pedido: '.Commons::renderError($pedido->getErrors()));
    }

    //TODO: Este metodo tiene que verificar si el pago fue hecho con tarjeta de credito
    public function esTarjetaCredito() {
      return true;
    }

    public function isEmbebbedPayment(){
      $PedidoFP = new self::$FormasPagosOnline[$this->id_medio_pago];
      return $PedidoFP->isEmbebbedPayment();
    }


    public function actualizarCrearPago($id,$estado,$transaction_amount,$total_paid_amount,$coupon_id,$extra = null,$transferencia_id = 0) {

      //comprobamos si el pago existe para no duplicar registros y actualizar el estado
      $RPago = null;
      $criteria            = new CDbCriteria;

      // Buscamos con id_servio_pago solo cuando el mismo esta relacionado con un medio de pago externo, caso contrario buscamos con el id del registro
      if ($this->isEmbebbedPayment()) {
        $criteria->condition = 'id_servicio_pago = '.$id;
      }
      else {
        $criteria->condition = 'id = '.$id;
      }

      $RPago               = PagosElectronicos::model()->find($criteria);

      if ($RPago == null) {
          $RPago                   = new PagosElectronicos;
          $RPago->id_pedido          = $this->id_pedido;
      }

      if ( $this->isEmbebbedPayment() ) {
        $RPago->id_servicio_pago = $id;
        $RPago->coupon_id          = $coupon_id;
      }
      else {
        $RPago->transferencia_id  = $transferencia_id;
      }

      $RPago->estado             = $estado;
      $RPago->extra = $extra;
      $RPago->id_medio_pago = $this->id_medio_pago;
      $RPago->transaction_amount = $transaction_amount;
      $RPago->total_paid_amount  = $total_paid_amount;

      
      if (!$RPago->save()){
        throw new Exception('RPago: '.$RPago->id.'.No se puedo actualizar:'.Commons::renderError($RPago->getErrors()));
      }

      return $RPago;

    }

     public function reservarStock(){
      //buscamos los productos correspondientes al carro de la web
      $registrosCarro = $this->obtenerItemsCarro();

      //Tengo que crear movimientos por reserva segun el stock disponible y prioridad de las bodegas
      foreach ($registrosCarro as $item) {
        // Obtengo stocks segun orden
        $stocks = ProductosStock::getStockProductos($item->producto_id,$item->categoria_id);
        //Itero y muevo a almacen online
        $cantidad = $item->cantidad;
        foreach ($stocks as $stock) {
            $cantidad =  $stock->moverAOnline($cantidad,$this->id_pedido);
            if ($cantidad <= 0)
                break;
        }
      }
    }

    public function desreservarStock(){
      //buscamos los productos correspondientes al carro de la web
      $registrosReservas = ProductosTransacciones::obtenerTransacciones('pedido_id',$this->id_pedido);

      //Tengo que crear movimientos por reserva segun el stock disponible y prioridad de las bodegas
      foreach ($registrosReservas as $transaccion) {

        //Revierto transaccion
        $transaccion->revertir();

      }
    }

    public static function listMediosEnvios(){
      $envios = [['medio_envio_id' =>self::FORMAENVIO_ENVIO,
                  'nombre' =>'Envio a Domicilio'],
                 ['medio_envio_id' =>self::FORMAENVIO_RETIROSUCURSAL,
                  'nombre' =>'Retiro en Sucursal'],
                  ['medio_envio_id' =>self::FORMAENVIO_ENVIO_RAPIDO,
                  'nombre' =>'Envio Rapido (Solo Rosario)']
                 ];

      return CHtml::listData($envios,'medio_envio_id','nombre');
    }

    public function realizarEnvio($bultos = 1) {
      ConfiguracionesWeb::getConfigWeb();
      $this->estado = PedidoOnline::ENTREGADO;


      
      if ($this->forma_envio == PedidoOnline::FORMAENVIO_ENVIO) {

        //Mando peticion a OCASA si es entrega a domicilio.

        $arrayOrigen = array('Nombre' => ConfiguracionesWeb::$envio_nombre,
                             'Domicilio' => ConfiguracionesWeb::$envio_domicilio,
                             'CodigoPostal' => ConfiguracionesWeb::$envio_codigo_postal,
                             'Provincia' => ConfiguracionesWeb::$envio_provincia,
                             'Localidad' =>  ConfiguracionesWeb::$envio_localidad,
                             'Telofono' =>  ConfiguracionesWeb::$envio_telefono,
                             'Pais' => ConfiguracionesWeb::$envio_pais);

        $direccionEnvio = $this->getDireccionEnvio();

        $arrayDestino = array('Nombre' => $direccionEnvio->getFullName(),
                              'Domicilio' => $direccionEnvio->getFullDomicilio(),
                              'CodigoPostal' => $direccionEnvio->cpostal,
                              'Provincia' => $direccionEnvio->provincia->provincia,
                              'Localidad' => $direccionEnvio->ciudad->ciudad,
                              'Telofono' => $direccionEnvio->getTelefono(),
                              'Pais' =>  ConfiguracionesWeb::$envio_pais);


        $arrayDetalle = array('CodigoCliente' => ConfiguracionesWeb::$envio_codigo_cliente,
                              'AcuerdoProducto' => ConfiguracionesWeb::$envio_acuerdo_cliente,
                              'AlternativoCliente' => $this->getNumero(),
                              'FechaRetiro' => date('d/m/Y'),
                              'HoraRetiroDesde' => '10:00:00',
                              'HoraRetiroHasta' => '20:00:00',
                              'Bultos' => $bultos);


        $return = OCASAProvider::WsGenerarRetiros($arrayOrigen, $arrayDestino, $arrayDetalle);


        if ($return['Status'] == 0){
          $this->codigo_seguimiento = $return['CodigoSeguimiento'];
        }
        else
           throw new Exception('Error al registrar envio en OCASA: '.$return['StatusText']);

         
      }

      if (!$this->save())
        throw new Exception('Error al actualizar estado del pedido: '.$pedido->getErrors());

      //Mando email con info tracking si es entrega a domicilio
      if ($this->forma_envio == PedidoOnline::FORMAENVIO_ENVIO || 
          $this->forma_envio == PedidoOnline::FORMAENVIO_ENVIO_RAPIDO) {
        EMailer::sendOrderSended($this);
      }

    }

    public function getComprobanteURL(){
      $ret = '';
      if ($this->estado == PedidoOnline::ENTREGADO && $this->forma_envio = PedidoOnline::FORMAENVIO_ENVIO) {

        $return = OCASAProvider::WsImprimirComprobante($this->codigo_seguimiento);


        if ($return['Status'] == 0){
         $ret = $return['Url'];
        }
        else
           throw new Exception('Error generar comprobante de OCASA: '.$return['StatusText']);

      }

      return $ret;
    }

  }
 ?>
