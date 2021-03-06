<?php

/**
 * This is the model class for table "productos_destacados".
 *
 * The followings are the available columns in table 'productos_destacados':
 * @property integer $producto_destacado_id
 * @property integer $categoria_id
 * @property integer $producto_id
 */
class ProductosDestacados extends CActiveRecord
{
    public $producto;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductosDestacados the static model class
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
        return 'productos_destacados';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('categoria_id, producto_id', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('producto_destacado_id, categoria_id, producto_id', 'safe', 'on'=>'search'),
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
            'producto_destacado_id' => 'Producto Destacado',
            'categoria_id' => 'Categoria',
            'producto_id' => 'Producto',
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

        $criteria->compare('producto_destacado_id',$this->producto_destacado_id);
        $criteria->compare('categoria_id',$this->categoria_id);
        $criteria->compare('producto_id',$this->producto_id);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    public static function seteoDestacado($producto_destacado = null, $categoria_id, $producto_id)
    {
        $sql = 'SELECT * FROM
                        productos_destacados WHERE categoria_id = '.$categoria_id.'
                        AND producto_id = '.$producto_id;
        $des = ProductosDestacados::model()->findBySql($sql);
        if ($producto_destacado && $des === null) {
            $pn = new ProductosDestacados();
            $pn->categoria_id = $categoria_id;
            $pn->producto_id = $producto_id;
            $pn->save();
        } else if (!$producto_destacado && $des !== null) {
            ProductosDestacados::model()->deleteAllByAttributes(
                array(
                    'categoria_id' => $categoria_id,
                    'producto_id' => $producto_id
                )
            );
        }
    }

    public static function isDestacado($producto_id, $categoria_id)
    {
        $sql = "SELECT * FROM productos_destacados WHERE producto_id = " . $producto_id . "
                AND categoria_id = " . $categoria_id;
        $destacados = ProductosDestacados::model()->findAllBySql($sql);

        return count($destacados) ? 1 : 0;
    }
}
