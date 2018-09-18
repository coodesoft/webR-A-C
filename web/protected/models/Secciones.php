<?php

/**
 * This is the model class for table "secciones".
 *
 * The followings are the available columns in table 'secciones':
 * @property integer $id_seccion
 * @property string $titulo_seccion
 * @property string $texto_seccion
 * @property string $subtitulo_seccion
 */
class Secciones extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Secciones the static model class
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
		return 'secciones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('titulo_seccion, subtitulo_seccion, texto_seccion', 'required'),
			array('titulo_seccion', 'length', 'max'=>255),
			array('subtitulo_seccion', 'length', 'max'=>50),
			array('texto_seccion', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_seccion, titulo_seccion, texto_seccion, subtitulo_seccion', 'safe', 'on'=>'search'),
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
			'id_seccion' => 'ID',
			'titulo_seccion' => 'Título',
			'subtitulo_seccion' => 'Subtítulo',
            'texto_seccion' => 'Texto',
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

		$criteria->compare('id_seccion',$this->id_seccion);
		$criteria->compare('titulo_seccion',$this->titulo_seccion,true);
		$criteria->compare('subtitulo_seccion',$this->subtitulo_seccion,true);
        $criteria->compare('texto_seccion',$this->texto_seccion,true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}