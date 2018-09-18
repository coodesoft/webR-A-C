<?php

/**
 * This is the model class for table "ciudades".
 *
 * The followings are the available columns in table 'ciudades':
 * @property integer $ciudad_id
 * @property integer $provincia_id
 * @property string $ciudad
 * @property integer $orden
 *
 * The followings are the available model relations:
 * @property Provincias $provincia
 */
class Ciudades extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Ciudades the static model class
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
        return 'ciudades';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('orden', 'required'),
            array('provincia_id, orden', 'numerical', 'integerOnly'=>true),
            array('ciudad', 'length', 'max'=>100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('ciudad_id, provincia_id, ciudad, orden', 'safe', 'on'=>'search'),
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
            'provincia' => array(self::BELONGS_TO, 'Provincias', 'provincia_id'),
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
            'ciudad_id' => 'Ciudad',
            'provincia_id' => 'Provincia',
            'ciudad' => 'Ciudad',
            'orden' => 'Orden',
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

        $criteria->compare('ciudad_id',$this->ciudad_id);
        $criteria->compare('provincia_id',$this->provincia_id);
        $criteria->compare('ciudad',$this->ciudad,true);
        $criteria->compare('orden',$this->orden);

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
     * @return elemento listData con las ciudades.
     *
     * */
    public static function listCiudades($provincia_id=null){

        $sql = "SELECT * FROM ciudades WHERE 1 = 1";
        if($provincia_id!==null){
            $sql .= " AND provincia_id = " . $provincia_id;
        }

        $sql .= " ORDER BY ciudad ASC";

        return CHtml::listData(Ciudades::model()->findAllBySql($sql),'ciudad_id','ciudad');
    }

}