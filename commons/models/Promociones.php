<?php

/**
 * This is the model class for table "promociones".
 *
 * The followings are the available columns in table 'promociones':
 * @property integer $promocion_id
 * @property integer $categoria_id
 * @property integer $producto_id
 * @property string $fecha_desde
 * @property string $fecha_hasta
 * @property double $porcentaje_promocion
 */
class Promociones extends CActiveRecord
{

    public $producto;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Promociones the static model class
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
        return 'promociones';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('categoria_id, producto_id, porcentaje_promocion, fecha_desde, fecha_hasta', 'required'),
            array('producto_id', 'numerical', 'integerOnly'=>true),
            array('porcentaje_promocion', 'numerical'),
            array('categoria_id, fecha_desde, fecha_hasta', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('promocion_id, categoria_id, producto_id, fecha_desde, fecha_hasta, porcentaje_promocion', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'categoria' => array(self::HAS_MANY, 'Categproas', 'categoria_id'),
        ];
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
            'promocion_id' => '#',
            'categoria_id' => 'Categoría',
            'producto_id' => 'Producto',
            'fecha_desde' => 'Fecha desde',
            'fecha_hasta' => 'Fecha hasta',
            'porcentaje_promocion' => 'Porcentaje',
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

        $criteria->compare('promocion_id',$this->promocion_id);
        $criteria->compare('categoria_id',$this->categoria_id);
        $criteria->compare('producto_id',$this->producto_id);
        $criteria->compare('fecha_desde',$this->fecha_desde,true);
        $criteria->compare('fecha_hasta',$this->fecha_hasta,true);
        $criteria->compare('porcentaje_promocion',$this->porcentaje_promocion);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    public static function getPromociones()
    {
        $sql = "SELECT * 
                FROM `promociones` 
                WHERE NOW() > fecha_desde 
                    AND (
                        NOW() < fecha_hasta 
                        OR fecha_hasta IS NULL 
                        OR fecha_hasta = '1970-01-01 00:00:00'
                    )";
        if ($categoria_id !== null && $producto_id !== null) {
            $sql .= " AND categoria_id = " . $categoria_id .
                    " AND producto_id = " . $producto_id;
        }
        $promociones = Promociones::model()->findAllBySql($sql);

        $porcentaje_online = ProductosPrecios::getPorcentajeOnline();
        $dif = round(1 - ($porcentaje_online / 100), 2);

        if (count($promociones)) {
            foreach ($promociones as $key => &$p) {
                $producto = Productos::getProductInfo($p->categoria_id, $p->producto_id);
                if ($producto === null) {
                    unset($promociones[$key]);
                    continue;
                }
                $p->producto = $producto;
            }
        }

        return $promociones;
    }

    public static function hasPromocion($categoria_id = null, $producto_id = null)
    {
        $sql = "SELECT * 
                FROM `promociones` 
                WHERE NOW() > fecha_desde 
                    AND (
                        NOW() < fecha_hasta 
                        OR fecha_hasta IS NULL 
                        OR fecha_hasta = '1970-01-01 00:00:00'
                    )";
        if ($categoria_id !== null && $producto_id !== null) {
            $sql .= " AND categoria_id = " . $categoria_id .
                    " AND producto_id = " . $producto_id;
        }
        $promocion = Promociones::model()->findBySql($sql);

        return $promocion !== null ? $promocion : false;
    }
}