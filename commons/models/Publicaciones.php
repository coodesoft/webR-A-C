<?php

/**
 * This is the model class for table "publicaciones".
 *
 * The followings are the available columns in table 'publicaciones':
 * @property integer $publicacion_id
 * @property string $titulo
 * @property string $bajada
 * @property string $texto
 * @property string $imagen_destacada
 * @property integer $activa
 * @property string $fecha_publicacion
 *
 * The followings are the available model relations:
 * @property PublicacionesToCategorias[] $publicacionesToCategoriases
 */
class Publicaciones extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Publicaciones the static model class
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
        return 'publicaciones';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('titulo, bajada', 'required'),
            array('activa, ampliar', 'numerical', 'integerOnly'=>true),
            array('titulo, imagen_destacada', 'length', 'max'=>255),
            array('texto, imagen_destacada, fecha_publicacion', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('publicacion_id, titulo, bajada, texto, imagen_destacada, activa, fecha_publicacion', 'safe', 'on'=>'search'),
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
            'publicacionesToCategoriases' => array(self::HAS_MANY, 'PublicacionesToCategorias', 'publicacion_id'),
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
            'publicacion_id' => '#',
            'titulo' => 'Título',
            'bajada' => 'Bajada',
            'texto' => 'Texto',
            'imagen_destacada' => 'Imagen destacada',
            'activa' => 'Activa',
            'ampliar' => 'Ampliar',
            'fecha_publicacion' => 'Fecha de publicación',
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

        $criteria->compare('publicacion_id',$this->publicacion_id);
        $criteria->compare('titulo',$this->titulo,true);
        $criteria->compare('bajada',$this->bajada,true);
        $criteria->compare('texto',$this->texto,true);
        $criteria->compare('imagen_destacada',$this->imagen_destacada,true);
        $criteria->compare('activa',$this->activa);
        $criteria->compare('fecha_publicacion',$this->fecha_publicacion,true);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    public static function getPublicacionesHome($limit)
    {
        $sql = "SELECT * 
                FROM publicaciones
                WHERE activa = 1
                AND fecha_publicacion <= NOW()
                ORDER BY fecha_publicacion";
        $publicaciones = Publicaciones::model()->findAllBySql($sql);

        return $publicaciones;
    }
}