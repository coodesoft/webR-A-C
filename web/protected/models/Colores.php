<?php

/**
 * This is the model class for table "colores".
 *
 * The followings are the available columns in table 'colores':
 * @property integer $color_id
 * @property string $etiqueta
 * @property string $hexa
 */
class Colores extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Colores the static model class
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
		return 'colores';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('etiqueta, hexa', 'required'),
			array('etiqueta', 'length', 'max'=>100),
			array('hexa', 'length', 'max'=>6),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('color_id, etiqueta, hexa', 'safe', 'on'=>'search'),
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
			'color_id' => '#',
			'etiqueta' => 'Etiqueta',
			'hexa' => 'Color',
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

		$criteria->compare('color_id',$this->color_id);
		$criteria->compare('etiqueta',$this->etiqueta,true);
		$criteria->compare('hexa',$this->hexa,true);

		return new CActiveDataProvider($this, array(
			'pagination' => array(  
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
		));
	}

	/**
     * Devuelve todos los colores activos
     */ 
    public static function getColores(){
        return CHtml::listData(Colores::model()->findAll(
                array(
                    'order' => "etiqueta"
                )
        ), 'color_id', 'etiqueta');
    }
}