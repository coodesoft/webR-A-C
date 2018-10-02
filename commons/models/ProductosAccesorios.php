<?php

/**
 * This is the model class for table "productos_accesorios".
 *
 * The followings are the available columns in table 'productos_accesorios':
 * @property integer $producto_accesorio_id
 * @property integer $categoria_id
 * @property integer $producto_id
 * @property integer $accesorio_categoria_id
 * @property integer $accesorio_producto_id
 */
class ProductosAccesorios extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductosAccesorios the static model class
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
        return 'productos_accesorios';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('categoria_id, producto_id, accesorio_categoria_id, accesorio_producto_id', 'required'),
            array('categoria_id, producto_id, accesorio_categoria_id, accesorio_producto_id', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('producto_accesorio_id, categoria_id, producto_id, accesorio_categoria_id, accesorio_producto_id', 'safe', 'on'=>'search'),
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
            'producto_accesorio_id' => 'Producto Accesorio',
            'categoria_id' => 'Categoria',
            'producto_id' => 'Producto',
            'accesorio_categoria_id' => 'Accesorio Categoria',
            'accesorio_producto_id' => 'Accesorio Producto',
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

        $criteria->compare('producto_accesorio_id',$this->producto_accesorio_id);
        $criteria->compare('categoria_id',$this->categoria_id);
        $criteria->compare('producto_id',$this->producto_id);
        $criteria->compare('accesorio_categoria_id',$this->accesorio_categoria_id);
        $criteria->compare('accesorio_producto_id',$this->accesorio_producto_id);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    public static function deleteAllAccesoriosByProducto($categoria_id, $producto_id)
    {
        $sql = "DELETE FROM productos_accesorios
                WHERE categoria_id = " . $categoria_id . "
                AND producto_id = " . $producto_id;
        Yii::app()->db->createCommand($sql)->execute();
    }

    public static function addAccesorioToProducto($categoria_id, $producto_id, $accesorio_categoria_id, $accesorio_producto_id)
    {
        $sql = "INSERT INTO productos_accesorios
                SET categoria_id = " . $categoria_id . ",
                producto_id = " . $producto_id . ", 
                accesorio_categoria_id = " . $accesorio_categoria_id . ",
                accesorio_producto_id = " . $accesorio_producto_id;
        Yii::app()->db->createCommand($sql)->execute();
    }

    public static function getRelatedAccesorios($categoria_id, $producto_id, $limit = 6)
    {
        $accesorios = ProductosAccesorios::model()->findAllByAttributes(
            array(
                'categoria_id' => $categoria_id,
                'producto_id' => $producto_id
            ),
            array(
                'limit' => $limit,
                'order' => 'RAND()'
            )
        );

        return $accesorios;
    }

    public static function isAccesorio($categoria_id, $producto_id, $accesorio_categoria_id, $accesorio_producto_id)
    {
        $accesorios = ProductosAccesorios::model()->findAllByAttributes(
            array(
                'categoria_id' => $categoria_id,
                'producto_id' => $producto_id,
                'accesorio_categoria_id' => $accesorio_categoria_id,
                'accesorio_producto_id' => $accesorio_producto_id
            )
        );

        return count($accesorios) ? true : false;
    }
}