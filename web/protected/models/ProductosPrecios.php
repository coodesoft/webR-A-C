<?php

/**
 * This is the model class for table "productos_precios".
 *
 * The followings are the available columns in table 'productos_precios':
 * @property integer $producto_precio_id
 * @property integer $categoria_id
 * @property integer $producto_id
 * @property integer $precio_id
 * @property double $precio
 * @property double $margen
 */
class ProductosPrecios extends CActiveRecord
{
    public $descuento;
    public $producto;
    public $precio_online;
    public $precio_tarjeta;
    public $precio_contado;

    const PRECIO_ONLINE_ID = 15;
    const PRECIO_TARJETA_ID = 12;
    const PRECIO_CONTADO_ID = 13;
    const PRECIO_AUX_ID = 99;
    const PRECIO_AUX_CUOTAS_SOBRE_PRECIO = 999;
    const PRECIO_AHORRO_ID = 777;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductosPrecios the static model class
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
        return 'productos_precios';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('categoria_id, producto_id, precio_id, precio','required'),
            array('categoria_id, producto_id, precio_id', 'numerical', 'integerOnly'=>true),
            array('precio, margen', 'numerical'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('producto_precio_id, categoria_id, producto_id, precio_id, precio, margen', 'safe', 'on'=>'search'),
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
            'precios' => array(self::BELONGS_TO, 'Precios', 'precio_id'),
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
            'producto_precio_id' => '#',
            'categoria_id' => 'Categoría',
            'producto_id' => 'Producto',
            'precio_id' => 'Precio',
            'precio' => 'Precio producto',
            'margen' => 'Margen'
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

        $criteria->compare('producto_precio_id',$this->producto_precio_id);
        $criteria->compare('categoria_id',$this->categoria_id);
        $criteria->compare('producto_id',$this->producto_id);
        $criteria->compare('precio_id',$this->precio_id);
        $criteria->compare('precio',$this->precio);
        $criteria->compare('margen',$this->margen);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
        ));
    }

    public static function getProductoPrecio($categoria_id, $producto_id, $precio_id)
    {
        return ProductosPrecios::model()->findByAttributes(
                                                            array(
                                                                'precio_id' => $precio_id,
                                                                'categoria_id' => $categoria_id,
                                                                'producto_id' => $producto_id,
                                                                )
                                                            );
    }

    /**
     * Devuelvo el porcentaje actual de descuento del precio online
     *
     * @return float
     */
    public static function getPorcentajeOnline()
    {
        $precio = Precios::model()->findByPk(ProductosPrecios::PRECIO_ONLINE_ID);
        return (double) $precio->porcentaje;
    }

    /**
     * Devuelvo todas las ofertas si no defino un producto
     * Si defino un producto y el resultado es una fila, el producto está en oferta
     *
     * @param null $categoria_id
     * @param null $producto_id
     * @return mixed
     */
    public static function getOfertas($categoria_id = null, $producto_id = null, $arrPromociones = null)
    {
        $porcentaje_online = self::getPorcentajeOnline();
        $dif = round(1 - ($porcentaje_online / 100), 2);

        $andProducto = "";
        if ($categoria_id !== null && $producto_id !== null) {
            $andProducto = " AND categoria_id = " . $categoria_id . " AND producto_id = " . $producto_id;
        }

        if (count($arrPromociones)) {
            $notIn = " AND (";
            $notInArr = array();
            foreach ($arrPromociones as $arr) {
                $notInArr[] = "(categoria_id != " . $arr['categoria_id'] . " AND producto_id != " . $arr['producto_id'] . ")";
            }
            $notIn .= implode(" OR ", $notInArr) . ")";
        }

        $sql = "SELECT 
                    cast((100 - (t1.po * 100 / t1.pt)) as DECIMAL) as descuento, 
                    t1.categoria_id, 
                    t1.producto_id, 
                    t1.po as precio_online, 
                    t1.pt as precio_tarjeta
                FROM
                    (
                    SELECT SUBSTRING_INDEX(t.r, ',',1) as pt, SUBSTRING_INDEX(t.r, ',',-1) as po, t.categoria_id, t.producto_id
                    FROM 
                        (
                        SELECT
                            p.categoria_id,
                            p.producto_id,
                            GROUP_CONCAT(p.precio) as r
                        FROM
                            productos_precios p
                        WHERE
                            p.precio_id IN (".self::PRECIO_TARJETA_ID.", ".self::PRECIO_ONLINE_ID.")
                            " . $andProducto . "
                            " . $notIn . "
                        GROUP BY 1, 2
                        ORDER BY 1, 2, 3
                        ) as t
                    ) as t1
                WHERE cast((100 - (t1.po * 100 / t1.pt)) as DECIMAL) > $porcentaje_online
                ORDER BY RAND() DESC
                LIMIT 6";

        $pp = ProductosPrecios::model()->findAllBySql($sql);

        $offerCount = 0;
        if (count($pp)) {
            foreach ($pp as $key => &$p) {
                // si estoy consultando un producto, no tengo que volver a consultarlo,
                // ademas si lo hago caigo en un bucle infinito
                if ($categoria_id === null && $producto_id === null) {
                    $producto = Productos::getProductInfo($p->categoria_id, $p->producto_id);
                    if ($producto === null) {
                        unset($pp[$key]);
                        continue;
                    }
                    $p->producto = $producto;
                }
                $p->producto->precio[self::PRECIO_TARJETA_ID] = $p->precio_tarjeta;
                $p->producto->precio[self::PRECIO_AUX_ID] = $p->precio_tarjeta * $dif; // precio con el 10 de desc para tacharlo
                $p->producto->precio[self::PRECIO_ONLINE_ID] = $p->precio_online;
                $p->producto->precio[self::PRECIO_CONTADO_ID] = $p->precio_contado;
                $p->producto->precio[self::PRECIO_AHORRO_ID] = $p->precio_tarjeta - $p->precio_online;
                $offerCount++;
                if ($offerCount == 6) {
                    return $pp;
                }
            }
        }

        return $pp;
    }
}