<?php

/**
 * This is the model class for table "bodegas_movimientos_productos".
 *
 * The followings are the available columns in table 'bodegas_movimientos_productos':
 * @property integer $bodega_movimiento_producto_id
 * @property integer $bodega_movimiento_id
 * @property integer $categoria_id
 * @property integer $producto_id
 * @property integer $cantidad
 * @property integer $cantidad_aux
 */
class BodegasMovimientosProductos extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BodegasMovimientosProductos the static model class
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
		return 'bodegas_movimientos_productos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bodega_movimiento_id, categoria_id, producto_id, cantidad, cantidad_aux', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('bodega_movimiento_producto_id, bodega_movimiento_id, categoria_id, producto_id, cantidad, cantidad_aux', 'safe', 'on'=>'search'),
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
			'categoria' => array(self::BELONGS_TO, 'Categorias', 'categoria_id'),
			'movimiento' => array(self::BELONGS_TO, 'BodegasMovimientos', 'bodega_movimiento_id'),
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
			'bodega_movimiento_producto_id' => '#',
			'bodega_movimiento_id' => '#',
			'categoria_id' => 'Categoría',
			'producto_id' => 'Producto',
			'cantidad' => 'Cantidad',
			'cantidad_aux' => 'Cantidad Aux',
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

		$criteria->compare('bodega_movimiento_producto_id',$this->bodega_movimiento_producto_id);
		$criteria->compare('bodega_movimiento_id',$this->bodega_movimiento_id);
		$criteria->compare('categoria_id',$this->categoria_id);
		$criteria->compare('producto_id',$this->producto_id);
		$criteria->compare('cantidad',$this->cantidad);
		$criteria->compare('cantidad_aux',$this->cantidad_aux);

		return new CActiveDataProvider($this, array(
			'pagination' => array(  
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
		));
	}
}