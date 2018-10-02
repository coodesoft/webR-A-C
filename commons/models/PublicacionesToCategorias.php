<?php

/**
 * This is the model class for table "publicaciones_to_categorias".
 *
 * The followings are the available columns in table 'publicaciones_to_categorias':
 * @property integer $publicacion_to_categoria_id
 * @property integer $publicacion_id
 * @property integer $publicacion_categoria_id
 *
 * The followings are the available model relations:
 * @property PublicacionesCategorias $publicacionCategoria
 * @property Publicaciones $publicacion
 */
class PublicacionesToCategorias extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PublicacionesToCategorias the static model class
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
		return 'publicaciones_to_categorias';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('publicacion_id, publicacion_categoria_id', 'required'),
			array('publicacion_id, publicacion_categoria_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('publicacion_to_categoria_id, publicacion_id, publicacion_categoria_id', 'safe', 'on'=>'search'),
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
			'publicacionCategoria' => array(self::BELONGS_TO, 'PublicacionesCategorias', 'publicacion_categoria_id'),
			'publicacion' => array(self::BELONGS_TO, 'Publicaciones', 'publicacion_id'),
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
			'publicacion_to_categoria_id' => 'Publicacion To Categoria',
			'publicacion_id' => 'Publicacion',
			'publicacion_categoria_id' => 'Publicacion Categoria',
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

		$criteria->compare('publicacion_to_categoria_id',$this->publicacion_to_categoria_id);
		$criteria->compare('publicacion_id',$this->publicacion_id);
		$criteria->compare('publicacion_categoria_id',$this->publicacion_categoria_id);

		return new CActiveDataProvider($this, array(
			'pagination' => array(  
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
		));
	}
}