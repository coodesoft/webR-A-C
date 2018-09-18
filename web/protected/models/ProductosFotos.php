<?php

/**
 * This is the model class for table "productos_fotos".
 *
 * The followings are the available columns in table 'productos_fotos':
 * @property integer $producto_foto_id
 * @property integer $producto_id
 * @property integer $color_id
 * @property string $foto
 * @property integer $predeterminada
 */
class ProductosFotos extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductosFotos the static model class
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
		return 'productos_fotos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('foto', 'required'),
			array('producto_id, color_id, predeterminada', 'numerical', 'integerOnly'=>true),
			array('foto', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('producto_foto_id, producto_id, color_id, foto, predeterminada', 'safe', 'on'=>'search'),
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
			'color' => array(self::BELONGS_TO, 'Colores', 'color_id'),
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
			'producto_foto_id' => 'Producto Foto',
			'producto_id' => 'Producto',
			'color_id' => 'Color',
			'foto' => 'Foto',
			'predeterminada' => 'Predeterminada',
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

		$criteria->compare('producto_foto_id',$this->producto_foto_id);
		$criteria->compare('producto_id',$this->producto_id);
		$criteria->compare('color_id',$this->color_id);
		$criteria->compare('foto',$this->foto,true);
		$criteria->compare('predeterminada',$this->predeterminada);

		return new CActiveDataProvider($this, array(
			'pagination' => array(  
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
		));
	}

	public static function getImagenPredeterminada($producto_id)
	{
		$model = ProductosFotos::model()->findByAttributes(array('predeterminada'=>1, 'producto_id'=>$producto_id));

		if($model === null || $model->foto == ""){
			// si no hay predeterminada, tomo una random
			$model = ProductosFotos::model()->findByAttributes(array('producto_id'=>$producto_id), array('order' => 'RAND()'));
			if($model === null || $model->foto == ""){
				// si aun no hay, devuelvo false y termino mostrando la que tenga el producto por defecto, si tiene.
				return false;
			}
		}

		return $model;
	}
}