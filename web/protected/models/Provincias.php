<?php

/**
 * This is the model class for table "provincias".
 *
 * The followings are the available columns in table 'provincias':
 * @property integer $provincia_id
 * @property string $provincia
 *
 * The followings are the available model relations:
 * @property Ciudades[] $ciudades
 */
class Provincias extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Provincias the static model class
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
        return 'provincias';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('provincia, codigoTP', 'length', 'max'=>100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('provincia_id, provincia, codigoTP', 'safe', 'on'=>'search'),
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
            'ciudades' => array(self::HAS_MANY, 'Ciudades', 'provincia_id'),
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
            'provincia_id' => 'Provincia',
            'provincia' => 'Provincia',
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

        $criteria->compare('provincia_id',$this->provincia_id);
        $criteria->compare('provincia',$this->provincia,true);
        $criteria->compare('codigoTP',$this->codigoTP,true);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    /**
     * Recupero una lista de ciudades para un dropDownList
     *
     * @return elemento listData con las provincias.
     *
     * */
    public static function listProvincias(){
        $criteria = new CDbCriteria();

        $criteria->order = "provincia ASC";

        return CHtml::listData(Provincias::model()->findAll($criteria),'provincia_id','provincia');
    }
}