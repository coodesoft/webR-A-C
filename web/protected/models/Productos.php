<?php

/**
 * This is the model class for table "productos".
 *
 * The followings are the available columns in table 'productos':
 * @property integer $producto_id
 * @property string $nombre
 * @property string $descripcion
 * @property string $numeracion
 * @property string $foto
 * @property integer $activo
 */
class Productos extends CActiveRecord
{
    public static $tableName;
    public static $prefix = "productos_";
    public $precio;
    public $novedad;
    public $oferta;
    public $promocion;
    public $isOferta;
    public $campos;
    public $categoria;
    public $cuotas_sobre_precio;
    public $multimedia;
    public $stock;

    const LABEL_ORDER_PRICE_LOW    = 'Precio más bajo';
    const VALUE_ORDER_PRICE_LOW    = 'priceLow';
    const LABEL_ORDER_PRICE_HIGH   = 'Precio más alto';
    const VALUE_ORDER_PRICE_HIGH   = 'priceHigh';
    const LABEL_ORDER_NAME_ASC     = 'A - Z';
    const VALUE_ORDER_NAME_ASC     = 'nameAsc';
    const LABEL_ORDER_NAME_DESC    = 'Z - A';
    const VALUE_ORDER_NAME_DESC    = 'nameDesc';
    const LABEL_ORDER_LAST_CREATED = 'Últimos productos';
    const VALUE_ORDER_LAST_CREATED = 'lastProds';
    const LABEL_ORDER_AHORRO_DESC  = 'Mayor ahorro';
    const VALUE_ORDER_AHORRO_DESC  = 'savings';

    public function __construct($table_name = '') {

        if ($table_name === null) {
            parent::__construct ( null );
        } else {
            self::$tableName = self::$prefix . $table_name;
            parent::__construct ();
        }
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductosLayegavoru the static model class
     */
    public static function model($table_name = '', $className = __CLASS__) {
        self::$tableName = self::$prefix . $table_name;
        return parent::model ( $className );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return self::$tableName;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
          ['nombre, descripcion, numeracion, foto', 'required'],
          ['activo', 'numerical', 'integerOnly'=>true],
          ['nombre, foto', 'length', 'max'=>255],
          // The following rule is used by search().
          // @todo Please remove those attributes that should not be searched.
          ['producto_id, nombre, descripcion, numeracion, foto, activo', 'safe', 'on'=>'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'fotos' => array(self::HAS_MANY, 'ProductosFotos', 'producto_id'),
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
      return [
        'producto_id' => 'Producto',
        'nombre'      => 'Nombre',
        'descripcion' => 'Descripcion',
        'numeracion'  => 'Numeracion',
        'foto'        => 'Foto',
        'activo'      => 'Activo',
      ];
    }

    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('producto_id',$this->producto_id);
        $criteria->compare('nombre',$this->nombre,true);
        $criteria->compare('descripcion',$this->descripcion,true);
        $criteria->compare('numeracion',$this->numeracion,true);
        $criteria->compare('foto',$this->foto,true);
        $criteria->compare('activo',$this->activo);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public static function getProductInfo($categoria_id, $producto_id)
    {
        ConfiguracionesWeb::cargar();

        $categoria = Categorias::model()->findByPk($categoria_id);

        $modelProd = new Productos($categoria->slug);
        if ($producto_id != "") {
            $sqlProd = "SELECT *
                        FROM productos_".$categoria->slug."
                        WHERE producto_id = " . $producto_id . "
                        AND borrado_logico = 0
                        AND activo_web = 1";
            $modelProd = $modelProd->findBySql($sqlProd);
        }

        if ($modelProd === null) {
            return null;
        }

        $precios = ProductosPrecios::model()->findAllByAttributes(
            array(
                'categoria_id' => $categoria_id,
                'producto_id' => $producto_id,
            )
        );

        if (!count($precios)) {
            return null;
        }
        foreach ($precios as $precio) {
            $modelProd->precio[$precio->precio_id] = $precio->attributes;
        }

        $configWeb = ConfiguracionesWeb::$Config;
        if ($configWeb->cuotas_sobre_precio !== ConfiguracionesWeb::CUOTAS_SOBRE_PRECIO_NO) {
            $modelProd->precio[ProductosPrecios::$PRECIO_AUX_CUOTAS_SOBRE_PRECIO] = new stdClass();
            $modelProd->precio[ProductosPrecios::$PRECIO_AUX_CUOTAS_SOBRE_PRECIO]->precio
                = $modelProd->precio[ProductosPrecios::$PRECIO_TARJETA_ID]['precio'] / $configWeb->cuotas_sobre_precio;
            $modelProd->cuotas_sobre_precio = (int) $configWeb->cuotas_sobre_precio;
        }

        $modelProd->novedad = self::isNovedad($categoria_id, $producto_id);

        $oferta = ProductosPrecios::getOfertas($categoria_id, $producto_id);
        $modelProd->isOferta = count($oferta) ? true : false;
        $modelProd->oferta = $modelProd->isOferta ? $oferta : null;

        $modelProd->campos = self::getCamposLabels($categoria_id);

        $modelProd->categoria = $categoria;

        $modelProd->multimedia = ProductosMultimedia::getMultimedia($categoria_id, $producto_id);

        $modelProd->stock = ProductosStock::getCantidadProductos($producto_id, $categoria_id);

        $promocion = Promociones::hasPromocion($categoria_id, $producto_id);
        if ($promocion !== false) {
            $modelProd->promocion = $promocion;
            $modelProd->isOferta = true;

            $modelProd->precio[ProductosPrecios::$PRECIO_ONLINE_ID]['precio']  = ((float) $modelProd->precio[ProductosPrecios::$PRECIO_ONLINE_ID]['precio'] * (100 - (float) $promocion->porcentaje_promocion)) / 100;
            $modelProd->precio[ProductosPrecios::$PRECIO_CONTADO_ID]['precio'] = ((float) $modelProd->precio[ProductosPrecios::$PRECIO_CONTADO_ID]['precio'] * (100 - (float) $promocion->porcentaje_promocion)) / 100;
        }

        $modelProd->precio[ProductosPrecios::$PRECIO_AHORRO_ID]['precio'] = $modelProd->precio[ProductosPrecios::$OFERTA_PRECIO_MAYOR_ID]['precio'] - $modelProd->precio[ProductosPrecios::$OFERTA_PRECIO_MENOR_ID]['precio'];
        $modelProd->precio[ProductosPrecios::$PRECIO_AUX_ID]['precio']    = $modelProd->precio[ProductosPrecios::$OFERTA_PRECIO_MAYOR_ID]['precio'];
        
        return $modelProd;
    }

    public static function isNovedad($categoria_id, $producto_id)
    {
      $novedad = ProductosNovedades::model()->findByAttributes(
          [
            'categoria_id' => $categoria_id,
            'producto_id' => $producto_id,
          ]
      );

      return $novedad !== null;
    }

    /**
     * Devuelvo todos los campos de una categoria
     * @param $categoria_id
     * @return array
     */
    public static function getCamposLabels($categoria_id)
    {
        $labels = CategoriasCampos::model()->findAllByAttributes(
            array(
                'categoria_id' => $categoria_id
            ),
            array(
                'order' => 'orden'
            )
        );

        $arrLabels = [];
        if ($labels !== null) {
            foreach ($labels as $label) {
                array_push($arrLabels, $label->attributes);
            }
        }

        return $arrLabels;
    }

    /**
     * Devuelvo todos los productos de una categoria, o una determinada cantidad de ellos
     *
     * @param $categoria_id
     * @param null $limit
     * @return array
     */
    public static function getProductosByCategoria($categoria_id, $limit = null, $keyword = null, $producto_id = null)
    {
        ConfiguracionesWeb::cargar();
        $categoria = Categorias::model()->findByPk($categoria_id);

        $sqlLimit = '';
        if ($limit !== null){
            $sqlLimit = " LIMIT " . $limit;
        }

        $sqlWhere = '';
        if ($keyword !== null) {
            $sqlWhere = " AND etiqueta LIKE '%" . $keyword . "%'";
        }

        if ($producto_id !== null) {
            $sqlWhere2 = " AND producto_id != " . $producto_id;
        }

        $productos = new Productos($categoria->slug);
        $sql = "SELECT *
                FROM productos_" . $categoria->slug . "
                WHERE borrado_logico = 0 AND activo_web = 1"
                . $sqlWhere
                . $sqlWhere2
                . " ORDER BY RAND() "
                . $sqlLimit;
        $productos = $productos->findAllBySql($sql);

        $arrProductos = [];
        foreach ($productos as $producto) {
            if (self::getProductInfo($categoria_id, $producto->producto_id) === null) {
                continue;
            }
            array_push($arrProductos, self::getProductInfo($categoria_id, $producto->producto_id));
        }

        return $arrProductos;
    }

    public static function getDestacados($limit = 15)
    {
        $destacados = ProductosDestacados::model()->findAll();

        $prods = 0;
        foreach ($destacados as $key => &$destacado) {
            $producto = Productos::getProductInfo($destacado->categoria_id, $destacado->producto_id);
            if ($producto === null) {
                unset($destacados[$key]);
                continue;
            }
            $destacado->producto = $producto;
            $prods++;
            if ($prods == $limit) {
                break;
            }
        }

        return $destacados;
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

        $arrProductos = [];
        foreach ($related as $producto) {
            if (self::getProductInfo($producto->producto_categoria_id, $producto->producto_producto_id) === null) {
                continue;
            }
            array_push($arrProductos, self::getProductInfo($producto->producto_categoria_id, $producto->producto_producto_id));
        }

        return $arrProductos;
    }

}
