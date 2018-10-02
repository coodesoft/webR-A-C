<?php

/**
 * This is the model class for table "configuraciones_web".
 *
 * The followings are the available columns in table 'configuraciones_web':
 * @property integer $configuracion_web_id
 * @property integer $mostrar_pedidos_incompletos
 * @property string $menu_keys
 * @property integer $mercado_pago_enabled
 * @property integer $todo_pago_enabled
 */
class ConfiguracionesWeb extends CActiveRecord
{
    const CUOTAS_SOBRE_PRECIO_NO = 'No';
    private static $Config = [];

    public static $envio_nombre = '';
    public static $envio_domicilio = '';
    public static $envio_codigo_postal = '';
    public static $envio_provincia = '';
    public static $envio_localidad = '';
    public static $envio_telefono = '';
    public static $envio_pais = '';

    public static $envio_url = '';
    public static $envio_username = '';
    public static $envio_password = '';
    public static $envio_codigo_cliente = '';
    public static $envio_acuerdo_cliente = '';

    private static $envio_config_fields = [
    ['field' => 'url', 'name' => 'url'],
    ['field' => 'username', 'name' => 'Username'],
    ['field' => 'password', 'name' => 'Password'],
    ['field' => 'codigo_cliente', 'name' => 'Codigo Cliente'],
    ['field' => 'acuerdo_cliente', 'name' => 'Acuerdo Cliente']];

    private static $envio_config_origin_fields = [
    ['field' => 'nombre', 'name' => 'Nombre'],
    ['field' => 'domicilio', 'name' => 'Domicilio'],
    ['field' => 'codigo_postal', 'name' => 'Codigo Postal'],
    ['field' => 'provincia', 'name' => 'Provincia'],
    ['field' => 'localidad', 'name' => 'Localidad'],
    ['field' => 'telefono', 'name' => 'Telefono'],
    ['field' => 'pais', 'name' => 'Pais']];

    public static $mailing_conf = null;

    private static $mailing_config_fields = [
    ['field' => 'url_web', 'name' => 'Url Web'],
    ['field' => 'telefono', 'name' => 'Telefono'],
    ['field' => 'mail', 'name' => 'Mail']];

    private static $mailing_orderSended_config_fields = [
    ['field' => 'orderSended_asunto', 'name' => 'Asunto'],
    ['field' => 'orderSended_dias', 'name' => 'Dias de demora']];

     private static $mailing_orderSendedFast_config_fields = [
    ['field' => 'orderSendedFast_horas', 'name' => 'Horas de demora'],
    ['field' => 'orderSendedFast_requisitos', 'name' => 'Requisitos']];

    private static $mailing_paymentReceived_config_fields = [
    ['field' => 'paymentReceived_asunto', 'name' => 'Asunto'],
    ['field' => 'paymentReceived_horarios', 'name' => 'Horario atencion'],
    ['field' => 'paymentReceived_requisitos_tarjeta', 'name' => 'Requisitos Tarjeta'],
    ['field' => 'paymentReceived_requisitos_transf', 'name' => 'Requisitos Transferencia']];

    private static $mailing_waitingPayment_config_fields = [
    ['field' => 'waitingPayment_asunto', 'name' => 'Asunto'],
    ['field' => 'waitingPayment_horas_limite', 'name' => 'Limite de espera (Hrs)']];

    private static function cargar($conf = null){
      //Actualizamos los valores estaticos de los precios de los productos
      ProductosPrecios::$PRECIO_ONLINE_ID        = $conf->lista_precio_online;
      ProductosPrecios::$PRECIO_TARJETA_ID       = $conf->lista_precio_cuotas;
      ProductosPrecios::$PRECIO_CONTADO_ID       = $conf->lista_precio_contado;
      ProductosPrecios::$PRECIO_LISTA_ID         = $conf->lista_precio_lista;
      ProductosPrecios::$PRECIO_TODOPAGO_ID      = $conf->lista_precio_todopago;
      ProductosPrecios::$PRECIO_MERCADOPAGO_ID      = $conf->lista_precio_mercado_pago;
      ProductosPrecios::$PRECIO_TRANSFERENCIA_ID = $conf->lista_precio_transferencia;
      ProductosPrecios::$PRECIO_RELACIONADO_ID = $conf->lista_precio_relacionado;
      ProductosPrecios::$PRECIO_COSEGURO_ID = $conf->lista_precio_coseguro;

      //obtenemos los identificadores de los tipos de precio definidos
      $TiposPrecios = self::getTiposPrecios();
      ProductosPrecios::$OFERTA_PRECIO_MENOR_ID = $conf[$TiposPrecios[$conf->oferta_tipo_precio_menor]['campo']];
      ProductosPrecios::$OFERTA_PRECIO_MAYOR_ID = $conf[$TiposPrecios[$conf->oferta_tipo_precio_mayor]['campo']];

    if ($conf == null)
      $conf = self::getConfigWeb();
    
    $envio_config = json_decode($conf->envios_config);

    self::$envio_nombre =   $envio_config->nombre;
    self::$envio_domicilio =   $envio_config->domicilio;
    self::$envio_codigo_postal =   $envio_config->codigo_postal;
    self::$envio_provincia =   $envio_config->provincia;
    self::$envio_localidad =   $envio_config->localidad;
    self::$envio_telefono =   $envio_config->telefono;
    self::$envio_pais =   $envio_config->pais;

    self::$envio_url =   $envio_config->url;
    self::$envio_username =   $envio_config->username;
    self::$envio_password =   $envio_config->password;
    self::$envio_codigo_cliente =  $envio_config->codigo_cliente;
    self::$envio_acuerdo_cliente = $envio_config->acuerdo_cliente;

    self::$mailing_conf = json_decode($conf->mailing_config);
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ConfiguracionesWeb the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'configuraciones_web';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email_recibe_notificaciones_ventas, lista_precio_contado, lista_precio_lista, lista_precio_cuotas, lista_precio_transferencia, lista_precio_online, lista_precio_mercado_pago, lista_precio_todopago, diferencia_porcen_is_oferta, oferta_tipo_precio_mayor, oferta_tipo_precio_menor, lista_precio_coseguro', 'required'),
            array('mercado_pago_enabled, todo_pago_enabled, comparador_enabled, cuotas_sobre_precio, mostrar_pedidos_incompletos, lista_precio_contado, lista_precio_lista, lista_precio_cuotas, lista_precio_transferencia, lista_precio_online, lista_precio_mercado_pago, lista_precio_todopago, diferencia_porcen_is_oferta, oferta_tipo_precio_mayor, oferta_tipo_precio_menor,lista_precio_relacionado,lista_precio_coseguro', 'numerical', 'integerOnly'=>true),
            array('menu_keys', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('configuracion_web_id, menu_keys, mercado_pago_enabled, todo_pago_enabled, comparador_enabled, cuotas_sobre_precio, mostrar_pedidos_incompletos, email_recibe_notificaciones_ventas, lista_precio_contado, lista_precio_lista, lista_precio_cuotas, lista_precio_transferencia, lista_precio_online, lista_precio_mercado_pago, lista_precio_todopago, diferencia_porcen_is_oferta, oferta_tipo_precio_mayor, oferta_tipo_precio_menor,lista_precio_relacionado,lista_precio_coseguro', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [];
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
            'configuracion_web_id' => 'Configuracion Web',
            'menu_keys'            => 'Menu Keys',
            'mercado_pago_enabled' => 'Mercado Pago Enabled',
            'todo_pago_enabled'    => 'Todo Pago Enabled',
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

        $criteria->compare('configuracion_web_id',$this->configuracion_web_id);
        $criteria->compare('menu_keys',$this->menu_keys,true);
        $criteria->compare('mercado_pago_enabled',$this->mercado_pago_enabled);
        $criteria->compare('todo_pago_enabled',$this->todo_pago_enabled);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    //si en el mismo hilo de ejecucion se necesita consultar la configuracion varias
    //veces no volvemos a hacer la consulta a la base de datos.
    public static function getConfigWeb()
    {
        if (self::$Config === []){
          self::$Config = ConfiguracionesWeb::model()->findByPk(1);
          self::cargar(self::$Config);
        }

        return self::$Config;
    }

    public static function getTiposPrecios(){
      return [
        '0' => ['campo' => 'lista_precio_online',        'nombre' => 'Precio Principal'],
        '1' => ['campo' => 'lista_precio_transferencia', 'nombre' => 'Precio Transferencia'],
        '2' => ['campo' => 'lista_precio_mercado_pago',  'nombre' => 'Precio Mercado Pago'],
        '3' => ['campo' => 'lista_precio_contado',       'nombre' => 'Precio Contado'],
        '4' => ['campo' => 'lista_precio_lista',         'nombre' => 'Precio Tachado'],
        '5' => ['campo' => 'lista_precio_cuotas',        'nombre' => 'Precio Cuotas'],
        '6' => ['campo' => 'lista_precio_todopago',      'nombre' => 'Precio Todo Pago'],
      ];
    }

    public static function getTiposPreciosCobro(){
      return [

        '1' => ['campo' => 'lista_precio_transferencia', 'nombre' => 'Precio Transferencia'],
        '2' => ['campo' => 'lista_precio_mercado_pago',  'nombre' => 'Precio Mercado Pago'],
        '6' => ['campo' => 'lista_precio_todopago',      'nombre' => 'Precio Todo Pago'],
        ];
    }

    public static function getTiposPreciosVisibles(){
      return [
        '0' => ['campo' => 'lista_precio_online',        'nombre' => 'Precio Principal'],
        '3' => ['campo' => 'lista_precio_contado',       'nombre' => 'Precio Contado'],
        '4' => ['campo' => 'lista_precio_lista',         'nombre' => 'Precio Tachado'],
        '5' => ['campo' => 'lista_precio_cuotas',        'nombre' => 'Precio Cuotas'],
        '7' => ['campo' => 'lista_precio_relacionado',        'nombre' => 'Precio Articulos relacionados'],
        '8' => ['campo' => 'lista_precio_coseguro',        'nombre' => 'Precio Base Coseguro'],
        
      ];
    }

    public static function getListaTiposPrecios(){
      $TPrecios = self::getTiposPrecios();
      $lista    = [];
      foreach ($TPrecios as $tprecio => $k)
        $lista[$tprecio] = $k['nombre'];

      return $lista;
    }

    public function getEnvioWSConfig(){
      $envio_config = json_decode($this->envios_config);
      $i = 0;
      foreach (self::$envio_config_fields as $config) {
         self::$envio_config_fields[$i]['value'] = $envio_config->{$config['field']};
          $i++;
      }

      return self::$envio_config_fields;
    }

    public function getEnvioOriginConfig(){
      $envio_config = json_decode($this->envios_config);
      $i = 0;
      foreach (self::$envio_config_origin_fields as $config) {
         self::$envio_config_origin_fields[$i]['value'] = $envio_config->{$config['field']};
          $i++;
      }

      return self::$envio_config_origin_fields;
    }

    public function getMailingConfig(){
      $mailing_config = json_decode($this->mailing_config);
      $i = 0;
      foreach (self::$mailing_config_fields as $config) {
         self::$mailing_config_fields[$i]['value'] = $mailing_config->{$config['field']};
          $i++;
      }

      return self::$mailing_config_fields;
    }

    public function getMailingWaitingPaymentConfig(){
      $mailing_config = json_decode($this->mailing_config);
      $i = 0;
      foreach (self::$mailing_waitingPayment_config_fields as $config) {
         self::$mailing_waitingPayment_config_fields[$i]['value'] = $mailing_config->{$config['field']};
          $i++;
      }

      return self::$mailing_waitingPayment_config_fields;
    }

    public function getMailingOrderSendedConfig(){
      $mailing_config = json_decode($this->mailing_config);
      $i = 0;
      foreach (self::$mailing_orderSended_config_fields as $config) {
         self::$mailing_orderSended_config_fields[$i]['value'] = $mailing_config->{$config['field']};
          $i++;
      }

      return self::$mailing_orderSended_config_fields;
    }

    public function getMailingOrderSendedFastConfig(){
      $mailing_config = json_decode($this->mailing_config);
      $i = 0;
      foreach (self::$mailing_orderSendedFast_config_fields as $config) {
         self::$mailing_orderSendedFast_config_fields[$i]['value'] = $mailing_config->{$config['field']};
          $i++;
      }

      return self::$mailing_orderSendedFast_config_fields;
    }

    public function getMailingPaymentReceivedConfig(){
      $mailing_config = json_decode($this->mailing_config);
      $i = 0;
      foreach (self::$mailing_paymentReceived_config_fields as $config) {
         self::$mailing_paymentReceived_config_fields[$i]['value'] = $mailing_config->{$config['field']};
          $i++;
      }

      return self::$mailing_paymentReceived_config_fields;
    }

    public function updateEnvioConfig($config){
      $envio_config = json_encode($config);
      $this->envios_config = $envio_config;
    }

    public function updateMailingConfig($config){
      $mailing_config = json_encode($config);
      $this->mailing_config = $mailing_config;
    }


}
