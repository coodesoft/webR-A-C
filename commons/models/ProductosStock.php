<?php

/**
 * This is the model class for table "productos_stock".
 *
 * The followings are the available columns in table 'productos_stock':
 * @property integer $id
 * @property integer $categoria_id
 * @property integer $producto_id
 * @property integer $bodega_id
 * @property integer $cantidad
 */
class ProductosStock extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductosStock the static model class
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
        return 'productos_stock';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('categoria_id, producto_id, bodega_id', 'numerical', 'integerOnly'=>true),
            array('cantidad', 'numerical'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, categoria_id, producto_id, bodega_id, cantidad', 'safe', 'on'=>'search'),
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
            'bodega' => array(self::BELONGS_TO, 'Bodegas', 'bodega_id'),
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
            'id' => 'ID',
            'categoria_id' => 'Categoria',
            'producto_id' => 'Producto',
            'bodega_id' => 'Bodega',
            'cantidad' => 'Cantidad',
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

        $criteria->compare('id',$this->id);
        $criteria->compare('categoria_id',$this->categoria_id);
        $criteria->compare('producto_id',$this->producto_id);
        $criteria->compare('bodega_id',$this->bodega_id);
        $criteria->compare('cantidad',$this->cantidad);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    protected function beforeSave(){

        //No permito stock negativo
        if ($this->cantidad < 0){
            $slug = Categorias::model()->findByPK($this->categoria_id)->slug;
            $producto = Productos::getProdInfo($slug,$this->producto_id);
            $this->addError('ProductosStock.cantidad', 'El stock no puede quedar en negativo para producto: '.$producto->etiqueta.', bodega: '.$this->bodega->nombre);
            return false;
        }

        return parent::beforeSave();
    }

    /**
     *    Devuelve la suma total de unidades de un producto dado y una bodega dada (opcional)
     *
     *    @param $producto_id
     *    @param $categoria_id
     *    @param $bodega_id optional
     *
     *    @return cantidad de productos
     */
    public static function getCantidadProductos($producto_id, $categoria_id, $bodega_id = null)
    {
        if($producto_id == '' || $categoria_id == '')
            return 0;

        $sql = "SELECT SUM(cantidad) as cantidadTotal
                FROM productos_stock ps, bodegas b
                WHERE ps.bodega_id = b.bodega_id AND b.suma_stock = 1
                    AND producto_id = " . $producto_id . "
                    AND categoria_id = " . $categoria_id;

        if ($bodega_id) {
            $sql .= " AND ps.bodega_id = " . $bodega_id;
        }

        $producto = Yii::app()->db->createCommand($sql)->queryAll();

        return $producto[0]["cantidadTotal"] != "" ? $producto[0]["cantidadTotal"] : 0;
    }

    public static function descontarStock($categoria_id, $producto_id, $bodega_id, $cantidad)
    {
        $criteria=new CDbCriteria;

        $criteria->compare('categoria_id',$categoria_id);
        $criteria->compare('producto_id',$producto_id);
        $criteria->compare('bodega_id',$bodega_id);

        $ps = ProductosStock::model()->findByAttributes(array("categoria_id"=>$categoria_id,"producto_id"=>$producto_id,"bodega_id"=>$bodega_id));

        $ps->cantidad -= $cantidad;
        if (!$ps->save())
          throw new Exception('Error al descontar stock: '.Commons::renderError($ps->getErrors()));
    }

    /**
     *    Devuelve los stocks disponibles para un producto, ordenados por orden de consumo (Prioridad), de los almacenes que suman stock
     *
     *    @param $producto_id
     *    @param $categoria_id
     *
     *    @return Productos Stock ordenados
     */
    public static function getStockProductos($producto_id, $categoria_id) {
        if($producto_id == '' || $categoria_id == '')
            return null;

        $criteria = new CDbCriteria;

        $criteria->with=[
            'bodega'=>[
                'joinType'=>'INNER JOIN',
                'condition'=>'bodega.suma_stock=1',
            ],
        ];
        $criteria->compare('categoria_id',$categoria_id);
        $criteria->compare('producto_id',$producto_id); 

        $criteria->order = 'bodega.orden ASC';

      return ProductosStock::model()->findAll($criteria);
    }

    public function moverAOnline($cantidad,$id_pedido){
        //Saco de bodega original
        $cantMover = min($cantidad,$this->cantidad);

        if ($cantMover > 0) {
          
          Bodegas::quitarDeBodega($this->categoria_id,$this->producto_id,$this->bodega_id,$cantMover);
          ProductosTransacciones::transaccion(ProductosTransacciones::TIPO_MOVIMIENTO_ONLINE_DESDE, $id_pedido, $this->bodega_id, $this->producto_id, $this->categoria_id, -$cantMover);

          //guardo en bodega reservas
          $bodegaOnline = Bodegas::getBodegaOnline();
          Bodegas::devolverABodega($this->categoria_id,$this->producto_id,$bodegaOnline,$cantMover);

          ProductosTransacciones::transaccion(ProductosTransacciones::TIPO_MOVIMIENTO_ONLINE_HASTA, $id_pedido, $bodegaOnline, $this->producto_id, $this->categoria_id, $cantMover);

        }
        //Retorno remanente
        return $cantidad - $cantMover;
    }

}
