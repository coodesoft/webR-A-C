<?php

/**
 * This is the model class for table "slides".
 *
 * The followings are the available columns in table 'slides':
 * @property integer $slider_id
 * @property string $imagen
 * @property string $descripcion
 * @property string $enlace
 * @property integer $activo
 * @property string $orden
 * @property string $tipo
 */
class Slides extends CActiveRecord
{
    const SLIDES_ALL = '"Todos"';
    const SLIDES_PRINCIPAL = '"Principal"';
    const SLIDES_SECUNDARIO = '"Home 1"';
    const SLIDES_SECUNDARIO_2 = '"Home 2"';
    const SLIDES_SECUNDARIO_3 = '"Home 3"';
    const SLIDES_MINI_1 = '"Mini Banner Home 1"';
    const SLIDES_MINI_2 = '"Mini Banner Home 2"';
    const SLIDES_MINI_3 = '"Mini Banner Home 3"';
    const SLIDES_CATEGORIA = '"Categoría"';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Slides the static model class
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
        return 'slides';
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
            array('activo', 'numerical', 'integerOnly'=>true),
            array('imagen, enlace, orden', 'length', 'max'=>255),
            array('enlace', 'url', 'allowEmpty' => true),
            array('tipo', 'length', 'max'=>18),
            array('descripcion', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('slider_id, imagen, descripcion, enlace, activo, orden, tipo', 'safe', 'on'=>'search'),
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
            'slider_id' => '#',
            'imagen' => 'Imagen',
            'descripcion' => 'Descripción',
            'enlace' => 'Link',
            'activo' => 'Activo',
            'orden' => 'Orden',
            'tipo' => 'Tipo',
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

        $criteria->compare('slider_id',$this->slider_id);
        $criteria->compare('imagen',$this->imagen,true);
        $criteria->compare('descripcion',$this->descripcion,true);
        $criteria->compare('enlace',$this->enlace,true);
        $criteria->compare('activo',$this->activo);
        $criteria->compare('orden',$this->orden,true);
        $criteria->compare('tipo',$this->tipo,true);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    public static function getSlides(array $tipos)
    {
        $sql = "SELECT * 
                FROM slides 
                WHERE activo = 1
                AND tipo IN (".implode(', ', $tipos).")
                ORDER BY orden";
        $slides = Slides::model()->findAllBySql($sql);

        return $slides;
    }
}