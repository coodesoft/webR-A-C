<?php

/**
 * This is the model class for table "productos_layegavoru".
 *
 * The followings are the available columns in table 'productos_layegavoru':
 * @property integer $producto_id
 * @property string $codigo
 * @property string $nombre
 * @property string $foto
 * @property string $descripcion
 * @property integer $stock
 * @property integer $stock_minimo
 * @property integer $borrado_logico
 * @property integer $activo_web
 * @property string $disponibilidad
 * @property string $field_jatugagibu
 * @property string $field_pobuxikuhu
 * @property string $field_cugisetava
 * @property string $field_nijasidota
 * @property string $field_warapudiva
 * @property string $field_dabamusigu
 */
class Productos extends CActiveRecord
{
    public $precio;
    public $cuotas_sobre_precio;
    public $novedad;
    public $isOferta;
    public $oferta;
    public $campos;
    public $categoria;
    public $multimedia;
    public $promocion;

    public static $tableName;
    public static $prefix = "productos_";

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
        return array(

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
            'fotos' => array(self::HAS_MANY, 'ProductosFotos', 'producto_id'),
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
            'producto_id' => '#',
            'codigo' => 'Código',
            'etiqueta' => 'Etiqueta',
            'marca' => 'Marca',
            'modelo' => 'Modelo',
            'foto' => 'Foto',
            'descripcion' => 'Descripción',
            'stock' => 'Stock',
            'stock_minimo' => 'Stock mínimo',
            'borrado_logico' => 'Eliminado',
            'activo_web' => 'Visible en web',
            'disponibilidad' => 'Disponibilidad',
            'porcentaje_coseguro' => 'Porcentaje Coseguro',
        );
    }

    public static function getListDisponibilidades($slug){

        $values = array(
                'SI' => 'SI',
                'NO' => 'NO',
                'A PEDIDO' => 'A PEDIDO',
            );

        return $values;
    }

    /**
     *    Recupero una lista de productos
     *    @param $bodega_id
     */
    public static function listAllProductos($bodega_id = null){
        $categorias = Categorias::model()->findAllByAttributes(array("tiene_atributos"=>1));

        foreach($categorias as $categoria){
            $sql = "SELECT p.* FROM productos_".$categoria->slug." p";
            if ($bodega_id) {
                $sql .= ", productos_stock ps WHERE p.producto_id = ps.producto_id
                        AND ps.categoria_id = " .$categoria->categoria_id."
                        AND ps.bodega_id = " .$bodega_id."
                        AND cantidad > 0 AND borrado_logico = 0";
            } else {
                        $sql .= " WHERE borrado_logico = 0";
            }

            $sql .= " ORDER BY p.codigo, p.etiqueta";
            $productos = Yii::app()->db->createCommand($sql)->queryAll();
            foreach($productos as $producto){
                $cantidad = "";
                if($bodega_id){
                    $cantidad = "_" . Productos::getCantidadStockByProductoAndBodega($categoria->categoria_id,$producto["producto_id"],$bodega_id);
                }
                $arrayProductos[$categoria->slug."_".$producto["producto_id"]."_s_".$categoria->tiene_numeros_serie.$cantidad] = $producto["codigo"]." ".$producto["etiqueta"];
            }

        }

        return $arrayProductos;

    }

    /**
     *  Recupero la cantidad en stock que hay de un producto dado
     */
    public static function getCantidadStockByProductoAndBodega($categoria_id = null, $producto_id = null, $bodega_id = null)
    {
        $sql = "SELECT SUM(cantidad) as cantidad FROM productos_stock ps WHERE 1 = 1";

        if($categoria_id){
            $sql .= " AND categoria_id = $categoria_id ";
        }
        if($producto_id){
            $sql .= " AND producto_id = $producto_id ";
        }
        if($bodega_id){
            $sql .= " AND bodega_id = $bodega_id ";
        }
        $sql .= " GROUP BY categoria_id, producto_id";

        $productos = Yii::app()->db->createCommand($sql)->queryAll();

        return $productos[0]["cantidad"];
    }

    /**
     * @Devuelve la el codigo de la categoria y el nombre todo en uno.
     * */
    public function getSlugID()
    {
        return trim(implode('_', array($this->slug, $this->producto_id)));
    }

    /**
     * @Devuelve la el codigo de la categoria y el nombre todo en uno.
     * */
    public function getCodigoYNombre()
    {
        return trim(implode(' ', array($this->codigo, $this->nombre)));
    }

    public static function getProdInfo($slug, $producto_id)
    {
        if (empty($slug))
            return null;

        $modelProd = new Productos($slug);

        if($producto_id != ""){
            $sqlProd = "SELECT * FROM productos_".$slug." WHERE producto_id = " . $producto_id;
            $modelProd = $modelProd->findBySql($sqlProd);
        }

        return $modelProd;
    }

    public static function getProductInfo($categoria_id, $producto_id)
    {
        $categoria = Categorias::model()->findByPk($categoria_id);

        if (empty($categoria->slug))
            return null;

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

        $modelProd->precio = [];

        foreach ($precios as $precio) {
            $modelProd->precio[$precio->precio_id] = $precio->attributes;
        }

        $configWeb = ConfiguracionesWeb::getConfigWeb();
        if ($configWeb->cuotas_sobre_precio !== ConfiguracionesWeb::CUOTAS_SOBRE_PRECIO_NO) {
            $modelProd->precio[ProductosPrecios::PRECIO_AUX_CUOTAS_SOBRE_PRECIO] = new stdClass();
            $modelProd->precio[ProductosPrecios::PRECIO_AUX_CUOTAS_SOBRE_PRECIO]->precio
                = $modelProd->precio[ProductosPrecios::PRECIO_TARJETA_ID]['precio'] / $configWeb->cuotas_sobre_precio;
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

            $modelProd->precio[ProductosPrecios::PRECIO_ONLINE_ID]['precio']  = ((float) $modelProd->precio[ProductosPrecios::PRECIO_ONLINE_ID]['precio'] * (100 - (float) $promocion->porcentaje_promocion)) / 100;
            $modelProd->precio[ProductosPrecios::PRECIO_CONTADO_ID]['precio'] = ((float) $modelProd->precio[ProductosPrecios::PRECIO_CONTADO_ID]['precio'] * (100 - (float) $promocion->porcentaje_promocion)) / 100;
        }

        $modelProd->precio[ProductosPrecios::PRECIO_AHORRO_ID]['precio'] = $modelProd->precio[ProductosPrecios::$OFERTA_PRECIO_MAYOR_ID]['precio'] - $modelProd->precio[ProductosPrecios::$OFERTA_PRECIO_MENOR_ID]['precio'];
        $modelProd->precio[ProductosPrecios::PRECIO_AUX_ID]['precio']    = $modelProd->precio[ProductosPrecios::$OFERTA_PRECIO_MAYOR_ID]['precio'];

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
    public static function getProductosByCategoria($categoria_id, $limit = null, $keyword = null, $producto_id = null,$marca_id = null, $modelo_id = null)
    {
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

        if ($marca_id !== null && $marca_id != 0 && $modelo_id != null && $modelo_id != 0) {
            $sqlWhere3 = " AND producto_id IN (SELECT producto_id from productos_modelos_productos where categoria_id = $categoria->categoria_id AND producto_modelo_id = $modelo_id ) ";
        }
        else {
             $sqlWhere3 = " AND producto_id NOT IN (SELECT producto_id from productos_modelos_productos where categoria_id = $categoria->categoria_id ) ";
        }
        /*   
            $sqlWhere3 = " AND producto_id IN (SELECT producto_id from productos_modelos_productos where categoria_id = $categoria->categoria_id AND producto_modelo_id IN (SELECT producto_modelo_id from productos_modelos where producto_marca_id = $marca_id ) ) ";
        }

        if ($modelo_id != null && $modelo_id != 0) {
            $sqlWhere4 = " AND producto_id IN (SELECT producto_id from productos_modelos_productos where categoria_id = $categoria->categoria_id AND producto_modelo_id = $modelo_id ) ";
        }
            if ($modelo_id !== null && $modelo_id != 0) {
                $sqlWhere4 = " AND producto_id NOT IN (SELECT producto_id from productos_modelos_productos where categoria_id = $categoria->categoria_id AND producto_modelo_id = $modelo_id ) ";
            }*/


        $productos = new Productos($categoria->slug);
        $sql = "SELECT *
                FROM productos_" . $categoria->slug . "
                WHERE borrado_logico = 0 AND activo_web = 1"
                . $sqlWhere
                . $sqlWhere2
                . $sqlWhere3
                //. $sqlWhere4
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

    public static function getMasVendidos($limit = 99)
    {
        $mas_vendidos = ProductosMasVendidos::model()->findAll();
        $return = [];
        $prods = 0;
        foreach ($mas_vendidos as $key => $mas_vendido) {
            $producto = Productos::getProductInfo($mas_vendido->categoria_id, $mas_vendido->producto_id);
            if ($producto === null) {
                continue;
            }
            $mas_vendido->producto = $producto;
            $return[] = $mas_vendido;

            $prods++;
            if ($prods == $limit) {
                break;
            }
        }

        return $return;
    }

    public static function getDestacados($limit = 99)
    {
        $destacados = ProductosDestacados::model()->findAll();
        $return = [];
        $prods = 0;
        foreach ($destacados as $key => $destacado) {
            $producto = Productos::getProductInfo($destacado->categoria_id, $destacado->producto_id);
            if ($producto === null) {
                continue;
            }
            $destacado->producto = $producto;
            $return[] = $destacado;

            if ($prods == $limit) {
                break;
            }
        }

        return $return;
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

    /**
     * metoyayexa_1_s_1 compuesta por
     *  slug categoria,
     *  id del producto de su tabla corresp,
     *  s es un separador,
     *  el ultimo define si tiene (1) nº de serie o no (0)
     */
    public static function unserializeSlug($code){
        $explode = explode("_",$code);
        return $explode[0];
    }

    /**
     * metoyayexa_1_s_1 compuesta por
     *  slug categoria,
     *  id del producto de su tabla corresp,
     *  s es un separador,
     *  el ultimo define si tiene (1) nº de serie o no (0)
     */
    public static function unserializeProductoID($code){
        $explode = explode("_",$code);
        return $explode[1];
    }

    /**
     * metoyayexa_1_s_1 compuesta por
     *  slug categoria,
     *  id del producto de su tabla corresp,
     *  s es un separador,
     *  el ultimo define si tiene (1) nº de serie o no (0)
     */
    public static function unserializeTieneSeries($code){
        $explode = explode("_",$code);
        return $explode[3];
    }

    public function primeraFotoGaleria(){
        $multimedia = ProductosMultimedia::getMultimedia($this->categoria->categoria_id, $this->producto_id);
        if ( sizeof($multimedia ) > 0 ) {
            return $multimedia[0]->url;
        }
        else return '';
    }
}
