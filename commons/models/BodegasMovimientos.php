<?php

/**
 * This is the model class for table "bodegas_movimientos".
 *
 * The followings are the available columns in table 'bodegas_movimientos':
 * @property integer $bodega_movimiento_id
 * @property integer $desde_bodega_id
 * @property integer $hasta_bodega_id
 * @property string $fecha
 * @property string $referencia
 * @property string $observaciones
 */
class BodegasMovimientos extends CActiveRecord
{
	const TIPO_INVENTARIO = 'INV';
	const TIPO_RMA = 'RMA'; 
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BodegasMovimientos the static model class
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
		return 'bodegas_movimientos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha, desde_bodega_id, hasta_bodega_id, referencia', 'required'),
			array('desde_bodega_id, hasta_bodega_id, user_id', 'numerical', 'integerOnly'=>true),
			array('hasta_bodega_id','compare','compareAttribute'=>'desde_bodega_id','operator'=>'!=','message'=>'No puede elegir la misma bodega de origen como destino.'),
			array('referencia', 'length', 'max'=>100),
			array('observaciones, fecha', 'length'),
			array('fecha_time', 'default', 'value' => round(microtime(true) * 10), 'on'=>'insert'),
			array('fecha_time', 'unique'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('bodega_movimiento_id, desde_bodega_id, hasta_bodega_id, fecha, referencia, fecha_time, observaciones, user_id, tipo', 'safe', 'on'=>'search'),
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
			'bodegaDesde' => array(self::BELONGS_TO, 'Bodegas', 'desde_bodega_id'),
			'bodegaHasta' => array(self::BELONGS_TO, 'Bodegas', 'hasta_bodega_id'),
			'productos' => array(self::HAS_MANY, 'BodegasMovimientosProductos', 'bodega_movimiento_id'),
			'usuario' => array(self::BELONGS_TO, 'User', 'user_id'),
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
			'bodega_movimiento_id' => '#',
			'desde_bodega_id' => 'Desde',
			'hasta_bodega_id' => 'Hacia',
			'fecha' => 'Fecha',
			'referencia' => 'Referencia',
			'observaciones' => 'Observaciones',
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

		$criteria->compare('bodega_movimiento_id',$this->bodega_movimiento_id);
		$criteria->compare('desde_bodega_id',$this->desde_bodega_id);
		$criteria->compare('hasta_bodega_id',$this->hasta_bodega_id);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('referencia',$this->referencia,true);
		$criteria->compare('observaciones',$this->observaciones,true);

		return new CActiveDataProvider($this, array(
			'pagination' => array(  
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
		));
	}

	public static function getNextReferencia()
	{
		$model = BodegasMovimientos::model()->find(array('order' => 'bodega_movimiento_id DESC', 'limit' => 1));

		$referencia = 1;

		if($model !== null) {
			$referencia = $model->bodega_movimiento_id + 1;
		}

		return str_pad($referencia, 8, 0, STR_PAD_LEFT);
	}

}