<?php

/**
 * This is the model class for table "slider_principal".
 *
 * The followings are the available columns in table 'slider_principal':
 * @property integer $id
 * @property string $imagen
 * @property integer $orden
 * @property string $url
 * @property string $titulo
 */
class SliderPrincipal extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SliderPrincipal the static model class
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
		return 'slider_principal';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('imagen', 'required'),
            array('url', 'url', 'allowEmpty'=>true),
			array('orden', 'numerical', 'integerOnly'=>true),
			array('imagen, url', 'length', 'max'=>255),
			array('titulo', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, imagen, orden, url, titulo', 'safe', 'on'=>'search'),
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'imagen' => 'Imagen',
			'orden' => 'Orden',
			'url' => 'Url',
			'titulo' => 'Título',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('imagen',$this->imagen,true);
		$criteria->compare('orden',$this->orden);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('titulo',$this->titulo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}