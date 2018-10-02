<?php

/**
 * This is the model class for table "productos_relacionados".
 *
 * The followings are the available columns in table 'productos_relacionados':
 * @property integer $producto_relacionado_id
 * @property integer $categoria_id
 * @property integer $producto_id
 * @property integer $producto_categoria_id
 * @property integer $producto_producto_id
 */
class ProductosRelacionados extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductosRelacionados the static model class
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
        return 'productos_relacionados';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('producto_relacionado_id, categoria_id, producto_id, producto_categoria_id, producto_producto_id', 'required'),
            array('producto_relacionado_id, categoria_id, producto_id, producto_categoria_id, producto_producto_id', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('producto_relacionado_id, categoria_id, producto_id, producto_categoria_id, producto_producto_id', 'safe', 'on'=>'search'),
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
            'producto_relacionado_id' => 'Producto Relacionado',
            'categoria_id' => 'Categoria',
            'producto_id' => 'Producto',
            'producto_categoria_id' => 'Producto Categoria',
            'producto_producto_id' => 'Producto Producto',
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

        $criteria->compare('producto_relacionado_id',$this->producto_relacionado_id);
        $criteria->compare('categoria_id',$this->categoria_id);
        $criteria->compare('producto_id',$this->producto_id);
        $criteria->compare('producto_categoria_id',$this->producto_categoria_id);
        $criteria->compare('producto_producto_id',$this->producto_producto_id);

        return new CActiveDataProvider($this, array(
            'pagination' => array(  
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    public static function deleteAllRelatedByProducto($categoria_id, $producto_id)
    {
        $sql = "DELETE FROM productos_relacionados
                WHERE categoria_id = " . $categoria_id . "
                AND producto_id = " . $producto_id;
        Yii::app()->db->createCommand($sql)->execute();
    }

    public static function addRelacionadoToProducto($categoria_id, $producto_id, $related_categoria_id, $related_producto_id)
    {
        $sql = "INSERT INTO productos_relacionados
                SET categoria_id = " . $categoria_id . ",
                producto_id = " . $producto_id . ", 
                producto_categoria_id = " . $related_categoria_id . ",
                producto_producto_id = " . $related_producto_id;
        Yii::app()->db->createCommand($sql)->execute();
    }

    public static function getRelatedProducts($categoria_id, $producto_id, $limit = 6)
    {
        $related = ProductosRelacionados::model()->findAllByAttributes(
            array(
                'categoria_id' => $categoria_id,
                'producto_id' => $producto_id
            ),
            array(
                'limit' => $limit,
                'order' => 'RAND()'
            )
        );

        return $related;
    }

    public static function isRelated($categoria_id, $producto_id, $related_categoria_id, $related_producto_id)
    {
        $related = ProductosRelacionados::model()->findAllByAttributes(
            array(
                'categoria_id' => $categoria_id,
                'producto_id' => $producto_id,
                'producto_categoria_id' => $related_categoria_id,
                'producto_producto_id' => $related_producto_id
            )
        );

        return count($related) ? true : false;
    }
}