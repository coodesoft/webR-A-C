<?php

/**
 * This is the model class for table "ventas_mercado_pago_items".
 *
 * The followings are the available columns in table 'ventas_mercado_pago_items':
 * @property integer $venta_mercado_pago_producto_id
 * @property integer $venta_mercado_pago_id
 * @property integer $category_id
 * @property integer $producto_id
 * @property string $title
 * @property string $description
 * @property string $picture_url
 * @property string $currency_id
 * @property integer $quantity
 * @property double $unit_price
 */
class VentasMercadoPagoItems extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return VentasMercadoPagoItems the static model class
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
        return 'ventas_mercado_pago_items';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('venta_mercado_pago_id, category_id, producto_id, title, description, picture_url, currency_id, quantity, unit_price', 'required'),
            array('venta_mercado_pago_id, category_id, producto_id, quantity', 'numerical', 'integerOnly'=>true),
            array('unit_price', 'numerical'),
            array('title, description', 'length', 'max'=>100),
            array('picture_url', 'length', 'max'=>255),
            array('currency_id', 'length', 'max'=>10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('venta_mercado_pago_producto_id, venta_mercado_pago_id, category_id, producto_id, title, description, picture_url, currency_id, quantity, unit_price', 'safe', 'on'=>'search'),
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
            'venta_mercado_pago_producto_id' => 'Venta Mercado Pago Producto',
            'venta_mercado_pago_id' => 'Venta Mercado Pago',
            'category_id' => 'Category',
            'producto_id' => 'Producto',
            'title' => 'Title',
            'description' => 'Description',
            'picture_url' => 'Picture Url',
            'currency_id' => 'Currency',
            'quantity' => 'Quantity',
            'unit_price' => 'Unit Price',
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

        $criteria->compare('venta_mercado_pago_producto_id',$this->venta_mercado_pago_producto_id);
        $criteria->compare('venta_mercado_pago_id',$this->venta_mercado_pago_id);
        $criteria->compare('category_id',$this->category_id);
        $criteria->compare('producto_id',$this->producto_id);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('picture_url',$this->picture_url,true);
        $criteria->compare('currency_id',$this->currency_id,true);
        $criteria->compare('quantity',$this->quantity);
        $criteria->compare('unit_price',$this->unit_price);

        return new CActiveDataProvider($this, array(
            'pagination' => array(  
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    public static function getItemsFromVentaMp($venta_mercado_pago_id)
    {
        return VentasMercadoPagoItems::model()->findAllByAttributes(
            array(
                'venta_mercado_pago_id' => $venta_mercado_pago_id
            )
        );
    }
}