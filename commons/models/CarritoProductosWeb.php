<?php

/**
 * This is the model class for table "carrito_productos_web".
 *
 * The followings are the available columns in table 'carrito_productos_web':
 * @property integer $carrito_producto_id
 * @property string $clave
 * @property integer $user_id
 * @property integer $categoria_id
 * @property integer $producto_id
 * @property integer $cantidad
 * @property string $fecha
 */
class CarritoProductosWeb extends CActiveRecord
{
    public $producto;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CarritoProductosWeb the static model class
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
        return 'carrito_productos_web';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['cantidad', 'required'],
            ['user_id, categoria_id, procesado, producto_id, cantidad', 'numerical', 'integerOnly'=>true],
            ['clave', 'length', 'max'=>80],
            ['fecha', 'safe'],
            ['fecha', 'default', 'value' => date('Y-m-d H:i:s'), 'on' => 'insert'],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['carrito_producto_id, clave, user_id, categoria_id, producto_id, cantidad, fecha', 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [];
    }

    /**
     * Función para formatear las fechas.
     * Todo lo que sea de tipo date y datetime serán formateados para ser legibles y antes de ser validados para almacanarse
     */
    public function behaviors(){
        return [
            'PFechas' => [
                'class' => 'ext.fechas.PFechas',
            ],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'carrito_producto_id' => 'Carrito Producto',
            'clave' => 'Clave',
            'user_id' => 'User',
            'categoria_id' => 'Categoria',
            'producto_id' => 'Producto',
            'cantidad' => 'Cantidad',
            'fecha' => 'Fecha',
        ];
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

        $criteria->compare('carrito_producto_id',$this->carrito_producto_id);
        $criteria->compare('clave',$this->clave,true);
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('categoria_id',$this->categoria_id);
        $criteria->compare('producto_id',$this->producto_id);
        $criteria->compare('cantidad',$this->cantidad);
        $criteria->compare('fecha',$this->fecha,true);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    public static function getCartKey(){
      return Yii::app()->session['cart_web_clave'];
    }

    /**
     * Recupero todos los items que tiene el user
     * Previamente elimino los que estan con cantidad 0
     *
     * @return mixed
     */
    public static function getCartItems($precio_id = null)
    {
        self::clearItems();

        $criteria            = new CDbCriteria;
        $criteria->select    = 'user_id, precio_unitario, categoria_id, producto_id, SUM(cantidad) as cantidad';
        $criteria->condition = 'id_pedido = 0';
        $criteria->group     = 'categoria_id, producto_id';

        if (Yii::app()->user->id == 0)
          $criteria->addCondition('clave = "'.self::getCartKey().'"'); 
        else
          $criteria->addCondition('user_id = '.Yii::app()->user->id); 

        $cartItems = self::model()->findAll($criteria);

        $total = 0;
        $itemsCount = 0;
        foreach ($cartItems as $key => $cartItem) {
            $producto = Productos::getProductInfo($cartItem->categoria_id, $cartItem->producto_id);
            if ($producto === null) {
                unset($cartItems[$key]);
                continue;
            }
            //Recargo precio del item con el precio pasado por parametro
            if ($precio_id != null){
              $cartItem->precio_unitario = Commons::formatPrice($producto->precio[$precio_id]['precio'],0,'');
            }

            $cartItem->producto = $producto;

            $precio = $cartItem->precio_unitario;
            $total += ($precio * $cartItem->cantidad);

            unset($cartItems[$key]->clave);
            $itemsCount += $cartItem->cantidad;
        }
        $cartItems = (object) array_merge( (array) $cartItems, ['total' => $total,'itemsCount' => $itemsCount]);

        return $cartItems;
    }

    /**
     * @param $categoria_id
     * @param $producto_id
     * @param int $cantidad
     * @return bool
     * @throws Exception
     */
    public static function addToCart($categoria_id, $producto_id, $cantidad = 1){
        if (Yii::app()->user->id != 0) {
            // tengo un usuario, asi que lo asocio derecho a este
            return self::add($categoria_id, $producto_id, $cantidad, 0, Yii::app()->user->id);
        } elseif (Yii::app()->session['cart_web_clave']) {
            // aun el usuario es un Guest, lo asocio a la clave
            return self::add($categoria_id, $producto_id, $cantidad, Yii::app()->session['cart_web_clave'], 0);
        } else {
            throw new Exception(500, 'No existe una sesión de usuario activa.');
        }
    }

    /**
     * @param $categoria_id
     * @param $producto_id
     * @param $cantidad
     * @param $clave
     * @param int $user_id
     * @return bool
     * @throws Exception
     */
    public static function add($categoria_id, $producto_id, $cantidad, $clave, $user_id = 0)
    {
        $cart = self::productoInCart($categoria_id, $producto_id, $clave, $user_id);
        if ($cart === null) {
            $cart               = new CarritoProductosWeb();
            $cart->clave        = $clave;
            $cart->user_id      = $user_id;
            $cart->categoria_id = $categoria_id;
            $cart->producto_id  = $producto_id;
            $cart->cantidad     = 0;
            $cart->id_pedido    = 0;

            ConfiguracionesWeb::getConfigWeb();
            $cart->id_precio    = ProductosPrecios::$PRECIO_ONLINE_ID;
        }
        $cart->fecha     = date('Y-m-d H:i:s');
        $cart->cantidad += $cantidad;
        //cargamos la info de los precios
        $producto = Productos::getProductInfo($categoria_id, $producto_id);
        $cart->precio_unitario = Commons::formatPrice($producto->precio[ProductosPrecios::$PRECIO_ONLINE_ID]['precio'],0,'');
        $cart->precio_total    = $cart->precio_unitario*$cantidad;
        if ($cart->save()) {
            return true;
        } else {
            throw new Exception(500, 'No se agregó el producto al carrito.');
        }
    }

    /**
     * @param $categoria_id
     * @param $producto_id
     * @param $clave
     * @param int $user_id
     * @return mixed
     */
    public static function productoInCart($categoria_id, $producto_id, $clave, $user_id = 0)
    {
      $criteria            = new CDbCriteria;
      $criteria->select    = 'carrito_producto_id, user_id, categoria_id, producto_id, SUM(cantidad) as cantidad';
      $criteria->group     = 'categoria_id, producto_id';
      $criteria->addcondition('categoria_id = '.$categoria_id);
      $criteria->addcondition('producto_id = '.$producto_id);
      $criteria->addcondition('id_pedido = 0 ');

      if ($user_id != 0) {
          $criteria->addcondition('user_id = '.$user_id);
      } else {
          $criteria->addcondition('clave = "'.$clave.'"');
      }

      return self::model()->find($criteria);
    }

    /**
     * Elimino todos los items del usuario que no tengan cantidades
     */
    public static function clearItems()
    {
        if (!Yii::app()->user->id) {
            $sql = "SELECT user_id, categoria_id, producto_id, SUM(cantidad) as cantidad
                    FROM carrito_productos_web
                    WHERE clave = '".Yii::app()->session['cart_web_clave']."'
                    AND id_pedido = 0
                    GROUP BY categoria_id, producto_id
                    HAVING cantidad <= 0";
            $cartItems = self::model()->findAllBySql($sql);

            foreach ($cartItems as $cartItem) {
                $sqlD = "DELETE FROM carrito_productos_web
                        WHERE categoria_id = " . $cartItem->categoria_id . "
                        AND producto_id = " . $cartItem->producto_id . "
                        AND clave = '".Yii::app()->session['cart_web_clave']."'
                        AND id_pedido = 0";
                Yii::app()->db->createCommand($sqlD)->execute();
            }
        } else {
            $sql = "SELECT user_id, categoria_id, producto_id, SUM(cantidad) as cantidad
                    FROM carrito_productos_web
                    WHERE user_id = ".Yii::app()->user->id."
                    AND id_pedido = 0
                    GROUP BY categoria_id, producto_id
                    HAVING cantidad <= 0";
            $cartItems = self::model()->findAllBySql($sql);

            foreach ($cartItems as $cartItem) {
                $sqlD = "DELETE FROM carrito_productos_web
                        WHERE categoria_id = " . $cartItem->categoria_id . "
                        AND producto_id = " . $cartItem->producto_id . "
                        AND user_id = ".Yii::app()->user->id. "
                        AND id_pedido = 0";
                Yii::app()->db->createCommand($sqlD)->execute();
            }
        }
    }

    // Verificamos si las cantidades indicadas en el carro son menores a la
    // del stock
    public static function inStock(){
      $items = self::getCartItems();
      $ret = [];
      foreach ($items as $item) {
          if (isset($item->producto) && ($item->producto['stock'] - $item['cantidad'] < 0)) {
              $ret[] = $item->producto;
          }
      }
      return $ret;
    }


    /**
     * Quito del carro un item especifico
     *
     * @param $categoria_id
     * @param $producto_id
     */
    public static function removeItemFromCart($categoria_id, $producto_id)
    {
        if (!Yii::app()->user->id) {
            $sql = "DELETE
                    FROM carrito_productos_web
                    WHERE clave = '".Yii::app()->session['cart_web_clave']."'
                    AND categoria_id = ".$categoria_id."
                    AND producto_id = ".$producto_id."
                    AND id_pedido = 0";
            Yii::app()->db->createCommand($sql)->execute();
        } else {
            $sql = "DELETE
                    FROM carrito_productos_web
                    WHERE user_id = ".Yii::app()->user->id."
                    AND categoria_id = ".$categoria_id."
                    AND producto_id = ".$producto_id."
                    AND id_pedido = 0";
            Yii::app()->db->createCommand($sql)->execute();
        }
    }

    public static function setProcesado($id_user,$id_pedido,$procesar = true){

        $criteria = new CDbCriteria;
        $criteria->condition = 'user_id = '.$id_user;
        $criteria->addCondition('id_pedido = 0');
        $registros = self::model()->findAll($criteria);
        foreach ($registros as $v) {
          if ($procesar)
            $v->id_pedido = $id_pedido;
          else
            $v->id_pedido = 0;
          
          $v->save(false);
         }
     }

    public static function setIdPedido($id){

    }

    // Calcula costo de envio segun los productos que componen el carrito
    public static function calcularCostoEnvio(){
      $items = self::getCartItems();
      $pesoTotal = 0;
      $coseguroAcum = 0;
      $costo = 0;
      foreach ($items as $item) {
        $pesoTotal += $item->producto->peso;

        //TODO: Ver de donde tomar el precio
        $precioCoseguro = Commons::formatPrice($item->producto->precio[ProductosPrecios::$PRECIO_COSEGURO_ID]['precio'],0,'');

        if ($item->producto->porcentaje_coseguro != null);
          $coseguroAcum = $coseguroAcum + ($item->producto->porcentaje_coseguro * ($precioCoseguro/100) );
      }

      $envioRango = EnviosRangos::obtenerRango($pesoTotal);
      $costo = $envioRango->costo_envio + $coseguroAcum;
      return $costo;
    }
}
