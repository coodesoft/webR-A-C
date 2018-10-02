<?php

/**
 * This is the model class for table "productos_multimedia".
 *
 * The followings are the available columns in table 'productos_multimedia':
 * @property integer $producto_multimedia_id
 * @property string $tipo
 * @property string $url
 * @property integer $activo
 */
class ProductosMultimedia extends CActiveRecord
{
    const TYPE_IMAGE = 'Imagen';
    const TYPE_YOUTUBE = 'Video Youtube';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductosMultimedia the static model class
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
        return 'productos_multimedia';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('url, producto_id, categoria_id', 'required'),
            array('activo', 'numerical', 'integerOnly'=>true),
            array('tipo', 'length', 'max'=>13),
            array('url', 'length', 'max'=>255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('producto_multimedia_id, tipo, url, activo', 'safe', 'on'=>'search'),
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
            'producto_multimedia_id' => '#',
            'tipo' => 'Tipo',
            'url' => 'Url',
            'activo' => 'Activo',
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

        $criteria->compare('producto_multimedia_id',$this->producto_multimedia_id);
        $criteria->compare('tipo',$this->tipo,true);
        $criteria->compare('url',$this->url,true);
        $criteria->compare('activo',$this->activo);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    public static function getMultimedia($categoria_id, $producto_id)
    {
        $sql = "SELECT * FROM productos_multimedia
                WHERE categoria_id = " . $categoria_id . "
                AND producto_id = " . $producto_id;
        $multimedia = ProductosMultimedia::model()->findAllBySql($sql);

        return $multimedia;
    }

    public static function getYoutubeId($url) {
        if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $id)) {
            $values = $id[1];
        } else if (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $id)) {
            $values = $id[1];
        } else if (preg_match('/youtube\.com\/v\/([^\&\?\/]+)/', $url, $id)) {
            $values = $id[1];
        } else if (preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $id)) {
            $values = $id[1];
        }
        else if (preg_match('/youtube\.com\/verify_age\?next_url=\/watch%3Fv%3D([^\&\?\/]+)/', $url, $id)) {
            $values = $id[1];
        } else {
            // not an youtube video
        }

        return $values;
    }
}