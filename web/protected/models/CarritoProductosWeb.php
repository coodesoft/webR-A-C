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
        return array(
            array('cantidad', 'required'),
            array('user_id, categoria_id, producto_id, cantidad', 'numerical', 'integerOnly'=>true),
            array('clave', 'length', 'max'=>80),
            array('fecha', 'safe'),
            array('fecha', 'default', 'value' => date('Y-m-d H:i:s'), 'on' => 'insert'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('carrito_producto_id, clave, user_id, categoria_id, producto_id, cantidad, fecha', 'safe', 'on'=>'search'),
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
            'carrito_producto_id' => 'Carrito Producto',
            'clave' => 'Clave',
            'user_id' => 'User',
            'categoria_id' => 'Categoria',
            'producto_id' => 'Producto',
            'cantidad' => 'Cantidad',
            'fecha' => 'Fecha',
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

    /**
     * Recupero todos los items que tiene el user
     * Previamente elimino los que estan con cantidad 0
     *
     * @return mixed
     */
    public static function getCartItems()
    {
        self::clearItems();

        if (!Yii::app()->user->id) {
            $sql = "SELECT user_id, categoria_id, producto_id, SUM(cantidad) as cantidad
                    FROM carrito_productos_web
                    WHERE clave = '".Yii::app()->session['cart_web_clave']."'
                    GROUP BY categoria_id, producto_id
                    ORDER BY fecha";
            $cartItems = self::model()->findAllBySql($sql);
        } else {
            $sql = "SELECT user_id, categoria_id, producto_id, SUM(cantidad) as cantidad
                    FROM carrito_productos_web
                    WHERE user_id = ".Yii::app()->user->id."
                    GROUP BY categoria_id, producto_id
                    ORDER BY fecha";
            $cartItems = self::model()->findAllBySql($sql);
        }

        $total = 0;
        $itemsCount = 0;
        foreach ($cartItems as $key => &$cartItem) {
            $producto = Productos::getProductInfo($cartItem->categoria_id, $cartItem->producto_id);
            if ($producto === null) {
                unset($cartItems[$key]);
                continue;
            }
            $cartItem->producto = $producto;

            $precio = $cartItem->producto->precio[ProductosPrecios::PRECIO_ONLINE_ID]['precio'];
            $total += ($precio * $cartItem->cantidad);

            unset($cartItems[$key]->clave);
            $itemsCount += $cartItem->cantidad;
        }

        $cartItems['total'] = $total;
        $cartItems['itemsCount'] = $itemsCount;

        return $cartItems;
    }

    /**
     * @param $categoria_id
     * @param $producto_id
     * @param int $cantidad
     * @return bool
     * @throws Exception
     */
    public static function addToCart($categoria_id, $producto_id, $cantidad = 1)
    {
        if (Yii::app()->user->id != 0) {
            // tengo un usuario, asi que lo asocio derecho a este
            return self::add($categoria_id, $producto_id, $cantidad, Yii::app()->session['cart_web_clave'], Yii::app()->user->id);
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
            $cart = new CarritoProductosWeb();
            $cart->clave = $clave;
            $cart->user_id = $user_id;
            $cart->categoria_id = $categoria_id;
            $cart->producto_id = $producto_id;
            $cart->cantidad = 0;
        }
        $cart->cantidad += $cantidad;
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
        if ($user_id != 0) {
            $sql = "SELECT carrito_producto_id, user_id, categoria_id, producto_id, SUM(cantidad) as cantidad
                    FROM carrito_productos_web
                    WHERE user_id = ".$user_id."
                    AND categoria_id = " . $categoria_id . "
                    AND producto_id = " . $producto_id . " 
                    GROUP BY categoria_id, producto_id
                    ORDER BY fecha DESC";
            $cart = self::model()->findBySql($sql);
        } else {
            $sql = "SELECT carrito_producto_id, user_id, categoria_id, producto_id, SUM(cantidad) as cantidad
                    FROM carrito_productos_web
                    WHERE clave = '".$clave."'
                    AND categoria_id = " . $categoria_id . "
                    AND producto_id = " . $producto_id . " 
                    GROUP BY categoria_id, producto_id
                    ORDER BY fecha DESC";
            $cart = self::model()->findBySql($sql);
        }

        return $cart;
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
                    GROUP BY categoria_id, producto_id
                    HAVING cantidad = 0";
            $cartItems = self::model()->findAllBySql($sql);

            foreach ($cartItems as $cartItem) {
                $sqlD = "DELETE FROM carrito_productos_web
                        WHERE categoria_id = " . $cartItem->categoria_id . "
                        AND producto_id = " . $cartItem->producto_id . " 
                        AND clave = '".Yii::app()->session['cart_web_clave']."'";
                Yii::app()->db->createCommand($sqlD)->execute();
            }
        } else {
            $sql = "SELECT user_id, categoria_id, producto_id, SUM(cantidad) as cantidad
                    FROM carrito_productos_web
                    WHERE user_id = ".Yii::app()->user->id."
                    GROUP BY categoria_id, producto_id
                    HAVING cantidad = 0";
            $cartItems = self::model()->findAllBySql($sql);

            foreach ($cartItems as $cartItem) {
                $sqlD = "DELETE FROM carrito_productos_web
                        WHERE categoria_id = " . $cartItem->categoria_id . "
                        AND producto_id = " . $cartItem->producto_id . " 
                        AND user_id = ".Yii::app()->user->id;
                Yii::app()->db->createCommand($sqlD)->execute();
            }
        }
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
                    AND producto_id = ".$producto_id;
            Yii::app()->db->createCommand($sql)->execute();
        } else {
            $sql = "DELETE
                    FROM carrito_productos_web
                    WHERE user_id = ".Yii::app()->user->id."
                    AND categoria_id = ".$categoria_id."
                    AND producto_id = ".$producto_id;
            Yii::app()->db->createCommand($sql)->execute();
        }
    }

    public static function dropUserCartAfterProcess()
    {
        if (Yii::app()->user->id) {
            $sql = "DELETE
                    FROM carrito_productos_web
                    WHERE user_id = ".Yii::app()->user->id;
            Yii::app()->db->createCommand($sql)->execute();
        }
    }
}