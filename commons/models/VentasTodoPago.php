<?php

/**
 * This is the model class for table "ventas_todo_pago".
 *
 * The followings are the available columns in table 'ventas_todo_pago':
 * @property integer $venta_todo_pago_id
 * @property integer $user_id
 * @property integer $estado_id
 * @property date $fecha
 * @property integer $merchant
 * @property integer $operationid
 * @property integer $currencycode
 * @property double $amount
 * @property string $csbtcity
 * @property string $csstcity
 * @property string $csbtcountry
 * @property string $csstcountry
 * @property string $csbtemail
 * @property string $csstemail
 * @property string $csbtfirstname
 * @property string $csstfirstname
 * @property string $csbtlastname
 * @property string $csstlastname
 * @property string $csbtphonenumber
 * @property string $csstphonenumber
 * @property string $csbtpostalcode
 * @property string $csstpostalcode
 * @property string $csbtstate
 * @property string $csststate
 * @property string $csbtstreet1
 * @property string $csststreet1
 * @property integer $csbtcustomerid
 * @property string $csbtipaddress
 * @property string $csptcurrency
 * @property string $csptgrandtotalamount
 * @property string $csmdd8
 * @property string $csitproductcode
 * @property string $csitproductdescription
 * @property string $csitproductname
 * @property string $csitproductsku
 * @property string $csittotalamount
 * @property string $csitquantity
 * @property string $csitunitprice
 */
class VentasTodoPago extends CActiveRecord
{
    const STATUS_PENDING = '0';
    const STATUS_OK = '1';
    const STATUS_ERROR = '2';
    const STATUS_INCOMPLETE = '3';

    const LABEL_STATUS_PENDING = 'Pendiente';
    const LABEL_STATUS_OK = 'Procesado';
    const LABEL_STATUS_ERROR = 'Error';
    const LABEL_STATUS_INCOMPLETE = 'Incompleto';

    public $names;
    public $ids;
    public $totales;
    public $cantidades;

    public function registrarPedido(){

    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return VentasTodoPago the static model class
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
        return 'ventas_todo_pago';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, fecha, merchant, operationid, currencycode, amount, csbtcity, csstcity, csbtcountry, csstcountry, csbtemail, csstemail, csbtfirstname, csstfirstname, csbtlastname, csstlastname, csbtphonenumber, csstphonenumber, csbtpostalcode, csstpostalcode, csbtstate, csststate, csbtstreet1, csststreet1, csbtcustomerid, csbtipaddress, csptcurrency, csptgrandtotalamount, csitproductcode, csitproductdescription, csitproductname, csitproductsku, csittotalamount, csitquantity, csitunitprice', 'required'),
            array('estado_id, merchant, operationid, currencycode, csbtcustomerid', 'numerical', 'integerOnly'=>true),
            array('amount', 'numerical'),
            array('csbtcity, csstcity, csbtcountry, csstcountry, csbtemail, csstemail, csbtfirstname, csstfirstname, csbtlastname, csstlastname, csbtphonenumber, csstphonenumber, csbtpostalcode, csstpostalcode, csbtstate, csststate, csbtstreet1, csststreet1', 'length', 'max'=>100),
            array('csbtipaddress', 'length', 'max'=>16),
            array('csptcurrency', 'length', 'max'=>10),
            array('csptgrandtotalamount, csmdd8', 'length', 'max'=>50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('venta_todo_pago_id, user_id, estado_id, fecha, merchant, operationid, currencycode, amount, csbtcity, csstcity, csbtcountry, csstcountry, csbtemail, csstemail, csbtfirstname, csstfirstname, csbtlastname, csstlastname, csbtphonenumber, csstphonenumber, csbtpostalcode, csstpostalcode, csbtstate, csststate, csbtstreet1, csststreet1, csbtcustomerid, csbtipaddress, csptcurrency, csptgrandtotalamount, csmdd8, csitproductcode, csitproductdescription, csitproductname, csitproductsku, csittotalamount, csitquantity, csitunitprice', 'safe', 'on'=>'search'),
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
            'venta_todo_pago_id' => 'Venta Todo Pago',
            'user_id' => 'User',
            'estado_id' => 'Estado',
            'fecha' => 'Fecha',
            'merchant' => 'Merchant',
            'operationid' => 'Operationid',
            'currencycode' => 'Currencycode',
            'amount' => 'Amount',
            'csbtcity' => 'Csbtcity',
            'csstcity' => 'Csstcity',
            'csbtcountry' => 'Csbtcountry',
            'csstcountry' => 'Csstcountry',
            'csbtemail' => 'Csbtemail',
            'csstemail' => 'Csstemail',
            'csbtfirstname' => 'Csbtfirstname',
            'csstfirstname' => 'Csstfirstname',
            'csbtlastname' => 'Csbtlastname',
            'csstlastname' => 'Csstlastname',
            'csbtphonenumber' => 'Csbtphonenumber',
            'csstphonenumber' => 'Csstphonenumber',
            'csbtpostalcode' => 'Csbtpostalcode',
            'csstpostalcode' => 'Csstpostalcode',
            'csbtstate' => 'Csbtstate',
            'csststate' => 'Csststate',
            'csbtstreet1' => 'Csbtstreet1',
            'csststreet1' => 'Csststreet1',
            'csbtcustomerid' => 'Csbtcustomerid',
            'csbtipaddress' => 'Csbtipaddress',
            'csptcurrency' => 'Csptcurrency',
            'csptgrandtotalamount' => 'Csptgrandtotalamount',
            'csmdd8' => 'Csmdd8',
            'csitproductcode' => 'Csitproductcode',
            'csitproductdescription' => 'Csitproductdescription',
            'csitproductname' => 'Csitproductname',
            'csitproductsku' => 'Csitproductsku',
            'csittotalamount' => 'Csittotalamount',
            'csitquantity' => 'Csitquantity',
            'csitunitprice' => 'Csitunitprice',
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

        $criteria->compare('venta_todo_pago_id',$this->venta_todo_pago_id);
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('estado_id',$this->estado_id);
        $criteria->compare('merchant',$this->merchant);
        $criteria->compare('operationid',$this->operationid);
        $criteria->compare('currencycode',$this->currencycode);
        $criteria->compare('amount',$this->amount);
        $criteria->compare('csbtcity',$this->csbtcity,true);
        $criteria->compare('csstcity',$this->csstcity,true);
        $criteria->compare('csbtcountry',$this->csbtcountry,true);
        $criteria->compare('csstcountry',$this->csstcountry,true);
        $criteria->compare('csbtemail',$this->csbtemail,true);
        $criteria->compare('csstemail',$this->csstemail,true);
        $criteria->compare('csbtfirstname',$this->csbtfirstname,true);
        $criteria->compare('csstfirstname',$this->csstfirstname,true);
        $criteria->compare('csbtlastname',$this->csbtlastname,true);
        $criteria->compare('csstlastname',$this->csstlastname,true);
        $criteria->compare('csbtphonenumber',$this->csbtphonenumber,true);
        $criteria->compare('csstphonenumber',$this->csstphonenumber,true);
        $criteria->compare('csbtpostalcode',$this->csbtpostalcode,true);
        $criteria->compare('csstpostalcode',$this->csstpostalcode,true);
        $criteria->compare('csbtstate',$this->csbtstate,true);
        $criteria->compare('csststate',$this->csststate,true);
        $criteria->compare('csbtstreet1',$this->csbtstreet1,true);
        $criteria->compare('csststreet1',$this->csststreet1,true);
        $criteria->compare('csbtcustomerid',$this->csbtcustomerid);
        $criteria->compare('csbtipaddress',$this->csbtipaddress,true);
        $criteria->compare('csptcurrency',$this->csptcurrency,true);
        $criteria->compare('csptgrandtotalamount',$this->csptgrandtotalamount,true);
        $criteria->compare('csmdd8',$this->csmdd8,true);
        $criteria->compare('csitproductcode',$this->csitproductcode,true);
        $criteria->compare('csitproductdescription',$this->csitproductdescription,true);
        $criteria->compare('csitproductname',$this->csitproductname,true);
        $criteria->compare('csitproductsku',$this->csitproductsku,true);
        $criteria->compare('csittotalamount',$this->csittotalamount,true);
        $criteria->compare('csitquantity',$this->csitquantity,true);
        $criteria->compare('csitunitprice',$this->csitunitprice,true);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    public static function updateStatus($operationid, $status)
    {
        $vtp = VentasTodoPago::model()->findByAttributes(
            array(
                'operationid' => $operationid
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

        $sql = "SELECT * FROM ventas_todo_pago
                WHERE user_id = " . Yii::app()->user->id . "
                ".$sqlInc."
                ORDER BY fecha DESC";

        $vtp = VentasTodoPago::model()->findAllBySql($sql);

        $ventas = array();
        foreach ($vtp as $v) {

            $v->names = explode('#', $v->csitproductname);
            $v->ids = explode('#', $v->csitproductsku);
            $v->totales = explode('#', $v->csittotalamount);
            $v->cantidades = explode('#', $v->csitquantity);

            $total = 0;
            $items = array();
            foreach($v->names as $key => $item) {
                $items[] = array(
                    'item' => $item,
                    'quantity' => $v->cantidades[$key],
                    'price' => $v->cantidades[$key] * $v->totales[$key]
                );

                $total += ($v->cantidades[$key] * $v->totales[$key]);
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
                'id' => $v->operationid,
                'fecha' => $v->fecha,
                'estado' => $estado_id,
                'total' => $total,
                'items' => $items
            );
        }

        return $ventas;

    }

    /**
     * Devuelvo el precio formateado valido para TP
     * @param $precio
     * @return float
     */
    public static function formatPrice($precio, $decimals = 2)
    {
        return number_format($precio, $decimals, '.', '');
    }

    public static function getAllPendientes()
    {
        $sql = "SELECT * FROM ventas_todo_pago
                WHERE estado_id = " . VentasTodoPago::STATUS_PENDING . "
                ORDER BY fecha DESC";

        $vtp = VentasTodoPago::model()->findAllBySql($sql);

        $ventas = array();
        foreach ($vtp as $v) {

            $v->names = explode('#', $v->csitproductname);
            $v->ids = explode('#', $v->csitproductsku);
            $v->totales = explode('#', $v->csittotalamount);
            $v->cantidades = explode('#', $v->csitquantity);

            $total = 0;
            $items = array();
            foreach($v->names as $key => $item) {
                $items[] = array(
                    'item' => $item,
                    'quantity' => $v->cantidades[$key],
                    'price' => $v->cantidades[$key] * $v->totales[$key]
                );

                $total += ($v->cantidades[$key] * $v->totales[$key]);
            }

            switch ($v->estado_id) {
                case self::STATUS_PENDING:
                    $estado_id = self::LABEL_STATUS_PENDING;
                    break;
            }

            $ventas[] = array(
                'id' => $v->operationid,
                'fecha' => $v->fecha,
                'estado' => $estado_id,
                'total' => $total,
                'items' => $items,
                'source' => 1
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
        $sql = "SELECT * FROM ventas_todo_pago
                WHERE operationid = " . $id . "
                LIMIT 1";

        $v = VentasTodoPago::model()->findBySql($sql);

        $cliente = Clientes::model()->findByAttributes(
            array(
                'user_id' => $v->user_id
            )
        );

        $venta = array();
        if ($v !== null) {

            $v->names = explode('#', $v->csitproductname);
            $v->ids = explode('#', $v->csitproductsku);
            $v->totales = explode('#', $v->csittotalamount);
            $v->cantidades = explode('#', $v->csitquantity);

            $total = 0;
            $items = array();
            foreach($v->names as $key => $item) {
                list($categoria_id, $producto_id) = explode('_', $v->ids[$key]);
                $items[] = array(
                    'item' => $item,
                    'quantity' => $v->cantidades[$key],
                    'price' => $v->cantidades[$key] * $v->totales[$key],
                    'unit_price' => $v->totales[$key],
                    'categoria_id' => (int) $categoria_id,
                    'producto_id' => (int) $producto_id
                );

                $total += ($v->cantidades[$key] * $v->totales[$key]);
            }

            switch ($v->estado_id) {
                case self::STATUS_PENDING:
                    $estado_id = self::LABEL_STATUS_PENDING;
                    break;
            }

            $venta = array(
                'id' => $v->operationid,
                'fecha' => $v->fecha,
                'estado' => $estado_id,
                'total' => $total,
                'items' => $items,
                'source' => 1,
                'cliente' => $cliente->attributes
            );
        }

        return $venta;
    }
}
