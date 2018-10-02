<?php

/**
 * This is the model class for table "ventas_mercado_pago".
 *
 * The followings are the available columns in table 'ventas_mercado_pago':
 * @property integer $venta_mercado_pago_id
 * @property string $external_reference
 * @property string $preference_id
 * @property integer $collector_id
 * @property string $operation_type
 * @property string $payer_name
 * @property string $payer_surname
 * @property string $payer_email
 * @property string $payer_date_created
 * @property string $payer_phone_area_code
 * @property string $payer_phone_number
 * @property string $payer_identification_type
 * @property string $payer_identification_number
 * @property string $payer_address_street_name
 * @property string $payer_address_street_number
 * @property string $payer_address_zip_code
 * @property integer $client_id
 * @property integer $shipments_free_shipping
 * @property double $shipments_cost
 * @property string $shipments_mode
 * @property string $shipments_receiver_address_street_name
 * @property string $shipments_receiver_address_street_number
 * @property string $shipments_receiver_address_zip_code
 * @property string $shipments_receiver_address_floor
 * @property string $shipments_receiver_address_apartment
 * @property string $date_created
 * @property string $collection_id
 * @property string $collection_status
 * @property string $payment_type
 * @property string $merchant_order_id
 */
class VentasMercadoPago extends CActiveRecord
{
    const STATUS_PENDING = '0';
    const STATUS_OK = '1';
    const STATUS_ERROR = '2';
    const STATUS_INCOMPLETE = '3';

    const LABEL_STATUS_PENDING = 'Pendiente';
    const LABEL_STATUS_OK = 'Procesado';
    const LABEL_STATUS_ERROR = 'Error';
    const LABEL_STATUS_INCOMPLETE = 'Incompleto';

    public function registrarPedido(){
      
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return VentasMercadoPago the static model class
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
        return 'ventas_mercado_pago';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, estado_id, external_reference, preference_id, collector_id, operation_type, payer_name, payer_surname, payer_email, payer_date_created, payer_phone_area_code, payer_phone_number, payer_identification_type, payer_identification_number, payer_address_street_name, payer_address_street_number, payer_address_zip_code, client_id, shipments_cost, shipments_mode, shipments_receiver_address_street_name, shipments_receiver_address_street_number, shipments_receiver_address_zip_code, shipments_receiver_address_floor, shipments_receiver_address_apartment, date_created, collection_id, collection_status, payment_type, merchant_order_id', 'required'),
            array('collector_id, client_id, shipments_free_shipping', 'numerical', 'integerOnly'=>true),
            array('shipments_cost', 'numerical'),
            array('external_reference, payer_phone_area_code, payer_identification_type, shipments_receiver_address_floor, shipments_receiver_address_apartment', 'length', 'max'=>10),
            array('preference_id', 'length', 'max'=>255),
            array('operation_type, payer_phone_number, shipments_mode, collection_id, collection_status, payment_type, merchant_order_id', 'length', 'max'=>50),
            array('payer_name, payer_surname, payer_email, payer_address_street_name, shipments_receiver_address_street_name', 'length', 'max'=>100),
            array('payer_identification_number, payer_address_street_number, payer_address_zip_code, shipments_receiver_address_street_number, shipments_receiver_address_zip_code', 'length', 'max'=>20),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('venta_mercado_pago_id, external_reference, preference_id, collector_id, operation_type, payer_name, payer_surname, payer_email, payer_date_created, payer_phone_area_code, payer_phone_number, payer_identification_type, payer_identification_number, payer_address_street_name, payer_address_street_number, payer_address_zip_code, client_id, shipments_free_shipping, shipments_cost, shipments_mode, shipments_receiver_address_street_name, shipments_receiver_address_street_number, shipments_receiver_address_zip_code, shipments_receiver_address_floor, shipments_receiver_address_apartment, date_created, collection_id, collection_status, payment_type, merchant_order_id', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'items' => array(self::HAS_MANY, 'VentasMercadoPagoItems', 'venta_mercado_pago_id'),
        );
    }

    /**
     * Función para formatear las fechas.
     * Todo lo que sea de tipo date y datetime serán formateados para ser legibles y antes de ser validados para almacanarse
     */
    public function behaviors(){
        return array(
            'PFechas' => array(
                'class' => 'ext.fechas.PFechas',
            ),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'venta_mercado_pago_id' => 'Venta Mercado Pago',
            'external_reference' => 'External Reference',
            'preference_id' => 'Preference',
            'collector_id' => 'Collector',
            'user_id' => 'User',
            'estado_id' => 'Estado',
            'operation_type' => 'Operation Type',
            'payer_name' => 'Payer Name',
            'payer_surname' => 'Payer Surname',
            'payer_email' => 'Payer Email',
            'payer_date_created' => 'Payer Date Created',
            'payer_phone_area_code' => 'Payer Phone Area Code',
            'payer_phone_number' => 'Payer Phone Number',
            'payer_identification_type' => 'Payer Identification Type',
            'payer_identification_number' => 'Payer Identification Number',
            'payer_address_street_name' => 'Payer Address Steet Name',
            'payer_address_street_number' => 'Payer Address Street Number',
            'payer_address_zip_code' => 'Payer Address Zip Code',
            'client_id' => 'Client',
            'shipments_free_shipping' => 'Shipments Free Shipping',
            'shipments_cost' => 'Shipments Cost',
            'shipments_mode' => 'Shipments Mode',
            'shipments_receiver_address_street_name' => 'Shipments Receiver Address Street Name',
            'shipments_receiver_address_street_number' => 'Shipments Receiver Address Street Number',
            'shipments_receiver_address_zip_code' => 'Shipments Receiver Address Zip Code',
            'shipments_receiver_address_floor' => 'Shipments Receiver Address Floor',
            'shipments_receiver_address_apartment' => 'Shipments Receiver Address Apartment',
            'date_created' => 'Date Created',
            'collection_id' => 'Collection',
            'collection_status' => 'Collection Status',
            'payment_type' => 'Payment Type',
            'merchant_order_id' => 'Merchant Order',
        );
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

        $criteria->compare('venta_mercado_pago_id',$this->venta_mercado_pago_id);
        $criteria->compare('external_reference',$this->external_reference,true);
        $criteria->compare('preference_id',$this->preference_id,true);
        $criteria->compare('collector_id',$this->collector_id);
        $criteria->compare('operation_type',$this->operation_type,true);
        $criteria->compare('payer_name',$this->payer_name,true);
        $criteria->compare('payer_surname',$this->payer_surname,true);
        $criteria->compare('payer_email',$this->payer_email,true);
        $criteria->compare('payer_date_created',$this->payer_date_created,true);
        $criteria->compare('payer_phone_area_code',$this->payer_phone_area_code,true);
        $criteria->compare('payer_phone_number',$this->payer_phone_number,true);
        $criteria->compare('payer_identification_type',$this->payer_identification_type,true);
        $criteria->compare('payer_identification_number',$this->payer_identification_number,true);
        $criteria->compare('payer_address_street_name',$this->payer_address_street_name,true);
        $criteria->compare('payer_address_street_number',$this->payer_address_street_number,true);
        $criteria->compare('payer_address_zip_code',$this->payer_address_zip_code,true);
        $criteria->compare('client_id',$this->client_id);
        $criteria->compare('shipments_free_shipping',$this->shipments_free_shipping);
        $criteria->compare('shipments_cost',$this->shipments_cost);
        $criteria->compare('shipments_mode',$this->shipments_mode,true);
        $criteria->compare('shipments_receiver_address_street_name',$this->shipments_receiver_address_street_name,true);
        $criteria->compare('shipments_receiver_address_street_number',$this->shipments_receiver_address_street_number,true);
        $criteria->compare('shipments_receiver_address_zip_code',$this->shipments_receiver_address_zip_code,true);
        $criteria->compare('shipments_receiver_address_floor',$this->shipments_receiver_address_floor,true);
        $criteria->compare('shipments_receiver_address_apartment',$this->shipments_receiver_address_apartment,true);
        $criteria->compare('date_created',$this->date_created,true);
        $criteria->compare('collection_id',$this->collection_id,true);
        $criteria->compare('collection_status',$this->collection_status,true);
        $criteria->compare('payment_type',$this->payment_type,true);
        $criteria->compare('merchant_order_id',$this->merchant_order_id,true);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    public static function updateStatus($external_reference, $status)
    {
        $vtp = VentasTodoPago::model()->findByAttributes(
            array(
                'external_reference' => $external_reference
            )
        );
        if ($vtp !== null) {
            $vtp->estado_id = $status;
            $vtp->save();
        }
    }

    public static function getPedidosByUser()
    {
        $config = ConfiguracionesWeb::getConfigWeb();
        if ($config->mostrar_pedidos_incompletos == 0) {
            $sqlInc = " AND estado_id != 3 ";
        }

        $sql = "SELECT * FROM ventas_mercado_pago
                WHERE user_id = " . Yii::app()->user->id . "
                ".$sqlInc."
                ORDER BY date_created DESC";

        $vtp = VentasMercadoPago::model()->findAllBySql($sql);

        $ventas = array();
        foreach ($vtp as $v) {

            $total = 0;
            $items = array();
            foreach($v->items as $item) {
                $items[] = array(
                    'item' => $item->title,
                    'quantity' => $item->quantity,
                    'price' => $item->quantity * $item->unit_price
                );

                $total += ($item->quantity * $item->unit_price);
            }

            switch ($v->estado_id) {
                case self::STATUS_OK:
                    $estado_id = self::LABEL_STATUS_OK;
                    break;
                case self::STATUS_PENDING:
                    $estado_id = self::LABEL_STATUS_PENDING;
                    break;
                case self::STATUS_ERROR:
                    $estado_id = self::LABEL_STATUS_ERROR;
                    break;
                case self::STATUS_INCOMPLETE:
                    $estado_id = self::LABEL_STATUS_INCOMPLETE;
                    break;
            }

            $ventas[] = array(
                'id' => $v->external_reference,
                'fecha' => $v->date_created,
                'estado' => $estado_id,
                'total' => $total,
                'items' => $items
            );
        }

        return $ventas;

    }

    /**
     * @return array
     */
    public static function getAllPendientes()
    {
        $sql = "SELECT * FROM ventas_mercado_pago
                WHERE estado_id = " . VentasMercadoPago::STATUS_PENDING . "
                ORDER BY date_created DESC";

        $vtp = VentasMercadoPago::model()->findAllBySql($sql);

        $ventas = array();
        foreach ($vtp as $v) {

            $total = 0;
            $items = array();
            foreach($v->items as $item) {
                $items[] = array(
                    'item' => $item->title,
                    'quantity' => $item->quantity,
                    'price' => $item->quantity * $item->unit_price
                );

                $total += ($item->quantity * $item->unit_price);
            }

            switch ($v->estado_id) {
                case self::STATUS_PENDING:
                    $estado_id = self::LABEL_STATUS_PENDING;
                    break;
            }

            $ventas[] = array(
                'id' => $v->external_reference,
                'fecha' => $v->date_created,
                'estado' => $estado_id,
                'total' => $total,
                'items' => $items,
                'source' => 2
            );
        }

        return $ventas;

    }

    /**
     * @param $id
     * @return array
     */
    public static function getPedido($id)
    {
        $sql = "SELECT * FROM ventas_mercado_pago
                WHERE external_reference = " . $id . "
                LIMIT 1";

        $v = VentasMercadoPago::model()->findBySql($sql);

        $cliente = Clientes::model()->findByAttributes(
            array(
                'user_id' => $v->user_id
            )
        );

        $venta = array();
        if ($v !== null) {

            $total = 0;
            $items = array();
            foreach($v->items as $item) {
                $items[] = array(
                    'item' => $item->title,
                    'quantity' => $item->quantity,
                    'price' => $item->quantity * $item->unit_price,
                    'unit_price' => $item->unit_price,
                    'categoria_id' => (int) $item->category_id,
                    'producto_id' => (int) $item->producto_id
                );

                $total += ($item->quantity * $item->unit_price);
            }

            switch ($v->estado_id) {
                case self::STATUS_PENDING:
                    $estado_id = self::LABEL_STATUS_PENDING;
                    break;
            }

            $venta = array(
                'id' => $v->external_reference,
                'fecha' => $v->date_created,
                'estado' => $estado_id,
                'total' => $total,
                'items' => $items,
                'source' => 2,
                'cliente' => $cliente->attributes
            );
        }

        return $venta;

    }
}
