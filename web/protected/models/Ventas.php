<?php

/**
 * This is the model class for table "ventas".
 *
 * The followings are the available columns in table 'ventas':
 * @property integer $venta_id
 * @property integer $bodega_id
 * @property integer $tipo_venta_id
 * @property integer $cliente_id
 * @property string $fecha
 * @property double $total
 * @property string $referencia
 * @property string $observacion
 * @property integer $estado_venta_id
 * @property double $costo_envio
 * @property double $descuento
 * @property double $impuesto
 * @property integer $user_id
 */
class Ventas extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Ventas the static model class
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
        return 'ventas';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('fecha, estado_venta_id, bodega_id, cliente_id', 'required'),
            array('bodega_id, cliente_id, tipo_venta_id, estado_venta_id, user_id', 'numerical', 'integerOnly'=>true),
            array('total, costo_envio, impuesto', 'numerical'),
            array('referencia', 'unique', 'allowEmpty' => true),
            array('referencia', 'length', 'max'=>255),
            array('fecha_time', 'default', 'value' => round(microtime(true) * 10), 'on'=>'insert'),
            array('fecha_time', 'unique'),
            array('clave', 'unique', 'allowEmpty' => true),
            array('observacion, descuento, clave', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('venta_id, bodega_id, tipo_venta_id, cliente_id, user_id, fecha, total, referencia, fecha_time, observacion, estado_venta_id, costo_envio, descuento, impuesto', 'safe', 'on'=>'search'),
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
            'bodega' => array(self::BELONGS_TO, 'Bodegas', 'bodega_id'),
            'cliente' => array(self::BELONGS_TO, 'Clientes', 'cliente_id'),
            'vendedor' => array(self::BELONGS_TO, 'User', 'user_id'),
            'ventasProductos' => array(self::HAS_MANY, 'VentasProductos', 'venta_id'),
            'ventasFormasPagos' => array(self::HAS_MANY, 'VentasFormasPagos', 'venta_id'),
            'anulaciones' => array(self::HAS_MANY, 'VentasAnulaciones', 'venta_id')
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
            'venta_id' => '#',
            'bodega_id' => 'Bodega',
            'tipo_venta_id' => 'Tipo de venta',
            'cliente_id' => 'Cliente',
            'fecha' => 'Fecha',
            'total' => 'Total',
            'referencia' => 'Nº referencia',
            'observacion' => 'Observación',
            'estado_venta_id' => 'Estado',
            'costo_envio' => 'Costo de envío',
            'descuento' => 'Descuento',
            'impuesto' => 'Impuesto',
            'user_id' => 'Vendedor',
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

        $criteria->compare('venta_id',$this->venta_id);
        $criteria->compare('bodega_id',$this->bodega_id);
        $criteria->compare('tipo_venta_id',$this->tipo_venta_id);
        $criteria->compare('cliente_id',$this->cliente_id);
        $criteria->compare('fecha',$this->fecha,true);
        $criteria->compare('total',$this->total);
        $criteria->compare('referencia',$this->referencia,true);
        $criteria->compare('observacion',$this->observacion,true);
        $criteria->compare('estado_venta_id',$this->estado_venta_id);
        $criteria->compare('costo_envio',$this->costo_envio);
        $criteria->compare('descuento',$this->descuento);
        $criteria->compare('impuesto',$this->impuesto);
        $criteria->compare('user_id',$this->user_id);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    public static function getNextReferencia()
    {
        $model = Ventas::model()->find(array('order' => 'venta_id DESC', 'limit' => 1));

        $referencia = 1;

        if($model !== null) {
            $referencia = $model->venta_id + 1;
        }

        return str_pad($referencia, 8, 0, STR_PAD_LEFT);
    }
}