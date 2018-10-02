<?php

/**
 * This is the model class for table "bodegas".
 *
 * The followings are the available columns in table 'bodegas':
 * @property integer $bodega_id
 * @property string $codigo
 * @property string $nombre
 * @property string $direccion
 * @property string $telefono
 */
class Bodegas extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Bodegas the static model class
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
        return 'bodegas';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nombre', 'required'),
            array('codigo, nombre, direccion, telefono', 'length', 'max'=>50),
            array('predeterminada, suma_stock, retiro_sucursal, orden, online, cuarentena, parent_bodega_id', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('bodega_id, codigo, nombre, direccion, telefono, online, retiro_sucursal, cuarentena, parent_bodega_id', 'safe', 'on'=>'search'),
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
            'ordenesRMA' => array(self::HAS_MANY, 'OrdenRMA', 'bodega_id'),
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
            'bodega_id' => '#',
            'codigo' => 'Código',
            'nombre' => 'Nombre',
            'direccion' => 'Dirección',
            'telefono' => 'Teléfono',
            'suma_stock' => 'Suma productos al stock general?',
            'predeterminada' => 'Bodega predeterminada',
            'orden' => 'Orden',
            'parent_bodega_id' => 'Bodega referencia',
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

        $criteria->compare('bodega_id',$this->bodega_id);
        $criteria->compare('predeterminada',$this->predeterminada);
        $criteria->compare('codigo',$this->codigo,true);
        $criteria->compare('nombre',$this->nombre,true);
        $criteria->compare('direccion',$this->direccion,true);
        $criteria->compare('telefono',$this->telefono,true);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    /**
     * @Devuelve la el codigo de la categoria y el nombre todo en uno.
     * */
    public function getCodigoYNombre()
    {
        return trim(implode(' ', array($this->codigo, $this->nombre)));
    }

    /**
     * Recupero una lista de bodegas para movimientos
     */
    public static function listBodegasMovimientos()
    {
        $criteria = new CDbCriteria();
        $criteria->compare("cuarentena", '<>1');
        $criteria->compare("online", '<>1');
        $criteria->order = "codigo ASC, nombre ASC";

        $bodegas = Bodegas::model()->findAll($criteria);

        return CHtml::listData($bodegas,'bodega_id','codigoYNombre');
    }

    /**
     * Recupero una lista de bodegas de cuarentena
     */
    public static function listBodegasCuarentena()
    {
        $criteria = new CDbCriteria();
        $criteria->compare("cuarentena", '1');

        $bodegas = Bodegas::model()->findAll($criteria);

        return CHtml::listData($bodegas,'bodega_id','codigoYNombre');
    }


    /**
     * Recupero un array de bodegas
     */
    public static function listBodegas($excluded=null, $all=false)
    {
        $bodegas = Bodegas::arrayBodegas($excluded, $all);

        return CHtml::listData($bodegas,'bodega_id','codigoYNombre');
    }

    /**
     * Recupero la lista de bodegas
     *
     * en $excluded defino las IDs de bodegas que no quiero que salgan el el resultado.
     * en $all defino si muestro todas las bodegas incluyendo las que no suman stock
     *
     * @return array de bodegas.
     *
     * */
    public static function arrayBodegas($excluded=null, $all=false)
    {
        $criteria = new CDbCriteria();
        if(isset($excluded) && count($excluded)>0)
            $criteria->addNotInCondition("bodega_id", $excluded);
        if ($all == false)
            $criteria->addCondition("suma_stock", 1);

        $criteria->order = "codigo ASC, nombre ASC";

        return Bodegas::model()->findAll($criteria);
    }

    public static function getBodegaPredeterminada()
    {
        $bodega = Bodegas::model()->findByAttributes(array('predeterminada'=>1));
        return $bodega->bodega_id;
    }

    /**
     * Reseteo el valor de todas las bodegas para que solo $bodega_id sea predeterminada = 1
     */
    public static function resetPredeterminada($bodega_id)
    {
        $sql = "UPDATE bodegas SET predeterminada = 0 WHERE bodega_id != " . $bodega_id;
        Yii::app()->db->createCommand($sql)->execute();
    }

    public static function getBodegasRetiroSucursal()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition("retiro_sucursal", 1);
        return Bodegas::model()->findAll($criteria);
    }

    public static function getBodegaCuarentena($bodega_referencia_id)
    {
        $bodega = Bodegas::model()->findByAttributes(array('cuarentena'=>1,'parent_bodega_id'=>$bodega_referencia_id));
        return $bodega->bodega_id;
    }

    public static function getBodegaOnline()
    {
        $bodega = Bodegas::model()->findByAttributes(array('online'=>1));
        return $bodega->bodega_id;
    }

    public static function getLastBodega()
    {
        $sql = "SELECT * FROM bodegas ORDER BY codigo DESC LIMIT 1";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    public static function getInfoBodegaActual(){
        return Bodegas::model()->findByPk(Yii::app()->session['bodega_id']);
    }

    public static function devolverABodega($categoria_id, $producto_id, $bodega_id, $cantidad)
    {
        // actualizo el stock del producto
        $ps = ProductosStock::model()->findByAttributes(
            array(
                'categoria_id' => $categoria_id,
                'producto_id' => $producto_id,
                'bodega_id' => $bodega_id
            )
        );
        if($ps===null){
            $ps = new ProductosStock();
            $ps->producto_id = $producto_id;
            $ps->categoria_id = $categoria_id;
            $ps->bodega_id = $bodega_id;
            $ps->cantidad = 0;
        }
        $ps->cantidad += $cantidad;

        if (!$ps->save())
            throw new Exception('Error agregando stock a bodega (id: bodega_id): '.Commons::renderError($ps->getErrors()));

        return true;
    }

    public static function quitarDeBodega($categoria_id, $producto_id, $bodega_id, $cantidad)
    {
        // actualizo el stock del producto
        $ps = ProductosStock::model()->findByAttributes(
            array(
                'categoria_id' => $categoria_id,
                'producto_id' => $producto_id,
                'bodega_id' => $bodega_id
            )
        );

        if($ps===null){
            throw new Exception('Error quitando stock de bodega (id: bodega_id): No se encontro stock para esta bodega (id_producto:'.$producto_id.' id_categoria:'.$categoria_id.')');
        }

        $ps->cantidad -= $cantidad;

        if ($ps->cantidad < 0) {
            return false;
        }

        if (!$ps->save())
            throw new Exception('Error quitando stock de bodega (id: bodega_id): '.Commons::renderError($ps->getErrors()));

        return true;
    }

    protected function beforeSave(){


        //TODO: No dejar que se cambie la bodega online si hay pedidos pendientes de procesar -> Pagados.

        //Solo una puede ser bodega online
        if ($this->online == 1) {
            if ($this->isNewRecord)
                 $sql = "UPDATE bodegas SET online = 0";
            else
                 $sql = "UPDATE bodegas SET online = 0 where bodega_id <> ".$this->bodega_id;
           
            Yii::app()->db->createCommand($sql)->execute();

            //La bodega online no puede ser de cuarentena ni controla stock
            $this->cuarentena = 0;
            $this->parent_bodega_id = 0;
            $this->predeterminada = 0;
            $this->suma_stock = 0;
            $this->orden = 0;

        }

        //Chequeo que tenga parent si es cuarentena
        if ($this->cuarentena == 1 && ($this->parent_bodega_id == 0 || $this->parent_bodega_id == null) ) {
            $this->addError('Bodegas.parent_bodega_id','Si la bodega es de cuarentena se debe seleccionar una bodega de referencia');
            return false;
        }

        //Actualizo marco en 0 las otras bodegas que no tienen parent o con parent igual a esta
        if ($this->cuarentena == 1) {
            $sql = "UPDATE bodegas SET cuarentena = 0 WHERE (parent_bodega_id = 0 OR parent_bodega_id is null OR parent_bodega_id = $this->parent_bodega_id)";
            Yii::app()->db->createCommand($sql)->execute();
        }

        return parent::beforeSave();
    }

    public function ordenesRMA()
    {
        $criteria = new CDbCriteria();

        $criteria->addCondition("bodega_id", $this->bodega_id);
        $criteria->order = "orden_rma_id desc";

        return OrdenRMA::model()->findAll($criteria);
    }


}
