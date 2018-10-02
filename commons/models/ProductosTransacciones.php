<?php

/**
 * This is the model class for table "productos_transacciones".
 *
 * The followings are the available columns in table 'productos_transacciones':
 * @property integer $productos_transacciones_id
 * @property string $fecha
 * @property integer $user_id
 * @property integer $producto_id
 * @property integer $categoria_id
 * @property integer $bodega_id
 * @property integer $cantidad
 * @property integer $compra_producto_id
 * @property integer $venta_producto_id
 * @property integer $bodega_movimiento_producto_id
 * @property integer $venta_anulacion_producto_id
 *
 * The followings are the available model relations:
 * @property ComprasProductos $compraProducto
 * @property BodegasMovimientosProductos $bodegaMovimientoProducto
 * @property VentasProductos $ventaProducto
 * @property VentasAnulacionesProductos $ventaAnulacionProducto
 */
class ProductosTransacciones extends CActiveRecord
{
	const TIPO_COMPRA_DEVOLUCION = 'C-';
    const TIPO_COMPRA_INGRESO = 'C+';
    const TIPO_VENTA_DEVOLUCION = 'V+';
    const TIPO_VENTA_EGRESO = 'V-';
    const TIPO_VENTA_CAMBIO = 'I-';
    const TIPO_MOVIMIENTO_DESDE = 'M-';
    const TIPO_MOVIMIENTO_HASTA = 'M+';
    const TIPO_STOCK_INICIAL = 'SI';
    const TIPO_RMA_REINGRESO_INGRESO = 'O+'; // Orden RMA
    const TIPO_RMA_REINGRESO_EGRESO = 'O-'; // Orden RMA
    const TIPO_RMA_RECHAZO = 'S-';
    const TIPO_MOVIMIENTO_ONLINE_DESDE = 'R-'; // Mov por reserva online
    const TIPO_MOVIMIENTO_ONLINE_HASTA = 'R+'; // Mov por reserva online
    const TIPO_MOVIMIENTO_ONLINE_EXPIRADO_DESDE = 'U+'; // Mov por desreserva online
    const TIPO_MOVIMIENTO_ONLINE_EXPIRADO_HASTA = 'U-'; // Mov por desreserva online
    const TIPO_VENTA_CANCELACION = 'E+';


	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProductosTransacciones the static model class
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
		return 'productos_transacciones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fecha, user_id, producto_id, categoria_id, bodega_id, cantidad, referencia', 'required'),
			array('user_id, producto_id, categoria_id, bodega_id, cantidad, compra_producto_id, venta_producto_id, bodega_movimiento_producto_id, venta_anulacion_producto_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('productos_transacciones_id, fecha, user_id, producto_id, categoria_id, bodega_id, cantidad, compra_producto_id, venta_producto_id, bodega_movimiento_producto_id, venta_anulacion_producto_id,pedido_id,referencia', 'safe', 'on'=>'search'),
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
			'compraProducto' => array(self::BELONGS_TO, 'ComprasProductos', 'compra_producto_id'),
			'bodegaMovimientoProducto' => array(self::BELONGS_TO, 'BodegasMovimientosProductos', 'bodega_movimiento_producto_id'),
			'ventaProducto' => array(self::BELONGS_TO, 'VentasProductos', 'venta_producto_id'),
			'ventaAnulacionProducto' => array(self::BELONGS_TO, 'VentasAnulacionesProductos', 'venta_anulacion_producto_id'),
			'bodega' => array(self::BELONGS_TO, 'Bodegas', 'bodega_id'),
			'pedido' => array(self::BELONGS_TO, 'PedidoOnline', 'pedido_id'),
			'ordenRMA' => array(self::BELONGS_TO, 'OrdenRMA', 'orden_rma_id'),
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
			'productos_transacciones_id' => 'Productos Transacciones',
			'fecha' => 'Fecha',
			'user_id' => 'User',
			'producto_id' => 'Producto',
			'categoria_id' => 'Categoria',
			'bodega_id' => 'Bodega',
			'cantidad' => 'Cantidad',
			'compra_producto_id' => 'Compra Producto',
			'venta_producto_id' => 'Venta Producto',
			'bodega_movimiento_producto_id' => 'Bodega Movimiento Producto',
			'venta_anulacion_producto_id' => 'Venta Anulacion Producto',
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

		$criteria->compare('productos_transacciones_id',$this->productos_transacciones_id);
		$criteria->compare('fecha',$this->fecha,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('producto_id',$this->producto_id);
		$criteria->compare('categoria_id',$this->categoria_id);
		$criteria->compare('bodega_id',$this->bodega_id);
		$criteria->compare('cantidad',$this->cantidad);
		$criteria->compare('compra_producto_id',$this->compra_producto_id);
		$criteria->compare('venta_producto_id',$this->venta_producto_id);
		$criteria->compare('bodega_movimiento_producto_id',$this->bodega_movimiento_producto_id);
		$criteria->compare('venta_anulacion_producto_id',$this->venta_anulacion_producto_id);

		return new CActiveDataProvider($this, array(
			'pagination' => array(  
                'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
            ),
            'criteria'=>$criteria,
		));
	}

	function salida(){
		return substr($this->tipo, -1) == "-";
	}

	function entrada(){
		return substr($this->tipo, -1) == "+";
	}

	function getEtiquetaProducto(){
		return $this->getProducto()->etiqueta;
	}

	function getProducto(){
		$slug = Categorias::model()->findByPK($this->categoria_id)->slug;
        $producto = Productos::getProdInfo($slug,$this->producto_id);
        return $producto;
	}

	function getCategoriaProducto(){
		return Categorias::model()->findByPK($this->categoria_id)->nombre;
	}

 	function getTipoText(){
 		switch($this->tipo){
 			case self::TIPO_COMPRA_DEVOLUCION:
 				return 'Egreso por Compra Anulada';
 			break;
 			case self::TIPO_COMPRA_INGRESO:
 				return 'Ingreso por Compra';
 			break;
 			case self::TIPO_VENTA_DEVOLUCION:
 				return 'Ingreso por Devolucion de Venta';
 			break;
 			case self::TIPO_VENTA_EGRESO:
 				return 'Egreso por Venta';
 			break;
 			case self::TIPO_VENTA_CAMBIO:
 				return 'Egreso por Cambio Producto Venta';
 			break;
 			case self::TIPO_MOVIMIENTO_DESDE:
 				return 'Movimiento Desde';
 			break;
 			case self::TIPO_MOVIMIENTO_HASTA:
 				return 'Movimiento Hasta';
 			break;
 			case self::TIPO_MOVIMIENTO_ONLINE_DESDE:
 				return 'Reserva por pedido online (desde)';
 			break;
 			case self::TIPO_MOVIMIENTO_ONLINE_HASTA:
 				return 'Reserva por pedido online (hasta)';
 			break;
 			case self::TIPO_STOCK_INICIAL:
 				return 'Stock Inicial';
 			break;
 			case self::TIPO_RMA_REINGRESO_EGRESO:
 				return 'Reingreso de mercaderia RMA';
 			break;
			case self::TIPO_RMA_REINGRESO_INGRESO:
				return 'Reingreso de mercaderia RMA';
			break;
			case self::TIPO_RMA_RECHAZO:
				return 'Rechazo de mercaderia RMA';
			break;
			case self::TIPO_MOVIMIENTO_ONLINE_EXPIRADO_HASTA:
				return 'Desreserva por pedido expirado (desde)';
			break;
			case self::TIPO_MOVIMIENTO_ONLINE_EXPIRADO_HASTA:
				return 'Desreserva por pedido expirado (hasta)';
			break;
 		}
 	}

 	public static function getTipoSQL($tableName = ''){
 		if ($tableName != '')
 			$tableName = $tableName.'.';
 		$sql =  'CASE ';
 		$sql .= ' WHEN '.$tableName.'tipo =\''.self::TIPO_COMPRA_DEVOLUCION.'\' THEN \'Egreso por Compra Anulada\'';
 		$sql .= ' WHEN '.$tableName.'tipo =\''.self::TIPO_COMPRA_INGRESO.'\' THEN \'Ingreso por Compra\'';
 		$sql .= ' WHEN '.$tableName.'tipo =\''.self::TIPO_VENTA_DEVOLUCION.'\' THEN \'Ingreso por Devolucion de Venta\'';
 		$sql .= ' WHEN '.$tableName.'tipo =\''.self::TIPO_VENTA_EGRESO.'\' THEN \'Egreso por Venta\'';
 		$sql .= ' WHEN '.$tableName.'tipo =\''.self::TIPO_VENTA_CANCELACION.'\' THEN \'Ingreso por Venta cancelada\'';
 		$sql .= ' WHEN '.$tableName.'tipo =\''.self::TIPO_VENTA_CAMBIO.'\' THEN \'Egreso por Cambio Producto Venta\'';
 		$sql .= ' WHEN '.$tableName.'tipo =\''.self::TIPO_MOVIMIENTO_DESDE.'\' THEN \'Movimiento Desde\'';
 		$sql .= ' WHEN '.$tableName.'tipo =\''.self::TIPO_MOVIMIENTO_HASTA.'\' THEN \'Movimiento Hasta\'';
 		$sql .= ' WHEN '.$tableName.'tipo =\''.self::TIPO_MOVIMIENTO_ONLINE_DESDE.'\' THEN \'Reserva por pedido online (desde)\'';
 		$sql .= ' WHEN '.$tableName.'tipo =\''.self::TIPO_MOVIMIENTO_ONLINE_HASTA.'\' THEN \'Reserva por pedido online (hasta)\'';
 		$sql .= ' WHEN '.$tableName.'tipo =\''.self::TIPO_STOCK_INICIAL.'\' THEN \'Stock Inicial\'';
 		$sql .= ' WHEN '.$tableName.'tipo =\''.self::TIPO_RMA_REINGRESO_EGRESO.'\' THEN \'Reingreso de mercaderia RMA\'';
 		$sql .= ' WHEN '.$tableName.'tipo =\''.self::TIPO_RMA_REINGRESO_INGRESO.'\' THEN \'Reingreso de mercaderia RMA\'';
 		$sql .= ' WHEN '.$tableName.'tipo =\''.self::TIPO_RMA_RECHAZO.'\' THEN \'Rechazo de mercaderia RMA\'';
		$sql .= ' WHEN '.$tableName.'tipo =\''.self::TIPO_MOVIMIENTO_ONLINE_EXPIRADO_HASTA.'\' THEN \'Desreserva por pedido expirado (desde)\'';
		$sql .= ' WHEN '.$tableName.'tipo =\''.self::TIPO_MOVIMIENTO_ONLINE_EXPIRADO_DESDE.'\' THEN \'Desreserva por pedido expirado (hasta)\'';
 		$sql .= ' END';
 		return $sql;
 	}

 	private function generarReferencia(){
 		switch($this->tipo){
 			case self::TIPO_COMPRA_DEVOLUCION:
 				return $this->compraProducto->compra->referencia;
 			break;
 			case self::TIPO_COMPRA_INGRESO:
 				return $this->compraProducto->compra->referencia;
 			break;
 			case self::TIPO_VENTA_DEVOLUCION:
 				return $this->ventaAnulacionProducto->anulacion->getNumero();
 			break;
 			case self::TIPO_VENTA_CANCELACION:
 				return $this->ventaProducto->venta->referencia != null && $this->ventaProducto->venta->referencia != '' ? $this->ventaProducto->venta->referencia : '--';
 			break;
 			case self::TIPO_VENTA_EGRESO:
 				return $this->ventaProducto->venta->referencia != null && $this->ventaProducto->venta->referencia != '' ? $this->ventaProducto->venta->referencia : '--';
 			break;
 			case self::TIPO_VENTA_CAMBIO:
 				return $this->ventaAnulacionProducto->anulacion->getNumero();
 			break;
 			case self::TIPO_MOVIMIENTO_DESDE:
 				return $this->bodegaMovimientoProducto->movimiento->referencia;
 			break;
 			case self::TIPO_MOVIMIENTO_HASTA:
 				return $this->bodegaMovimientoProducto->movimiento->referencia;
 			break;
 			case self::TIPO_MOVIMIENTO_ONLINE_HASTA:
			case self::TIPO_MOVIMIENTO_ONLINE_DESDE:
				if ($this->pedido)
					return $this->pedido->getNumero();
			break;
			case self::TIPO_MOVIMIENTO_ONLINE_EXPIRADO_HASTA:
			case self::TIPO_MOVIMIENTO_ONLINE_EXPIRADO_DESDE:
				if ($this->pedido)
					return $this->pedido->getNumero();
			break;
 			case self::TIPO_RMA_REINGRESO_EGRESO:
			case self::TIPO_RMA_REINGRESO_INGRESO:
			case self::TIPO_RMA_RECHAZO:
				return $this->ordenRMA->getNumero();
			break;
 		}
 	}

	/* 
	 * Crea una transaccion de producto, El record ID se almacena en la columna 
	 * correspondiente segun el tipo de transaccion.
	 * En caso de error arroja una Exception
	 */

	public static function transaccion($tipo, $record_id, $bodega_id, $producto_id, $categoria_id, $cantidad ){
		$transaccion = new ProductosTransacciones();
		$transaccion->cantidad = $cantidad;
		$transaccion->producto_id = $producto_id;
		$transaccion->categoria_id = $categoria_id;
		$transaccion->bodega_id = $bodega_id;
		$transaccion->fecha = date('Y-m-d H:i:s');
		$transaccion->tipo = $tipo;

		if (isset(Yii::app()->session['vendedor_id']))
			$transaccion->user_id = Yii::app()->session['vendedor_id'];
		else
			$transaccion->user_id = Yii::app()->user->id;

		switch ($tipo) {
			case self::TIPO_MOVIMIENTO_HASTA:
				$transaccion->bodega_movimiento_producto_id = $record_id;
			break;
			case self::TIPO_MOVIMIENTO_DESDE:
				$transaccion->bodega_movimiento_producto_id = $record_id;
			break;
			case self::TIPO_MOVIMIENTO_ONLINE_HASTA:
				$transaccion->pedido_id = $record_id;
			break;
			case self::TIPO_MOVIMIENTO_ONLINE_DESDE:
				$transaccion->pedido_id = $record_id;
			break;
			case self::TIPO_MOVIMIENTO_ONLINE_EXPIRADO_HASTA:
			case self::TIPO_MOVIMIENTO_ONLINE_EXPIRADO_DESDE:
				$transaccion->pedido_id = $record_id;
			case self::TIPO_VENTA_EGRESO:
			case self::TIPO_VENTA_CANCELACION:
				$transaccion->venta_producto_id = $record_id;
			break;
			case self::TIPO_VENTA_DEVOLUCION:
				$transaccion->venta_anulacion_producto_id = $record_id;
			break;
			case self::TIPO_VENTA_CAMBIO:
				$transaccion->venta_anulacion_producto_id = $record_id;
			break;
			case self::TIPO_COMPRA_INGRESO:
				$transaccion->compra_producto_id = $record_id;
			break;
			case self::TIPO_COMPRA_DEVOLUCION:
				$transaccion->compra_producto_id = $record_id;
			break;
			case self::TIPO_RMA_REINGRESO_EGRESO:
			case self::TIPO_RMA_REINGRESO_INGRESO:
			case self::TIPO_RMA_RECHAZO:
				$transaccion->orden_rma_id = $record_id;
			break;
		};

		$transaccion->referencia = $transaccion->generarReferencia();

		if (!$transaccion->save())
			throw new Exception('Error generando transaccion de producto: '.Commons::renderError($transaccion->getErrors()));
	}

	public function revertir(){
		switch ($this->tipo) {
			case self::TIPO_MOVIMIENTO_ONLINE_HASTA:

				Bodegas::quitarDeBodega($this->categoria_id,$this->producto_id,$this->bodega_id,$this->cantidad);

				ProductosTransacciones::transaccion(ProductosTransacciones::TIPO_MOVIMIENTO_ONLINE_EXPIRADO_HASTA, $this->pedido_id, $this->bodega_id, $this->producto_id, $this->categoria_id, -$this->cantidad);

			break;
			case self::TIPO_MOVIMIENTO_ONLINE_DESDE:

				//Multiplico por menos 1 porque el cantidad esta en negativa
				Bodegas::devolverABodega($this->categoria_id,$this->producto_id,$this->bodega_id,-$this->cantidad);

				ProductosTransacciones::transaccion(ProductosTransacciones::TIPO_MOVIMIENTO_ONLINE_EXPIRADO_DESDE, $this->pedido_id, $this->bodega_id, $this->producto_id, $this->categoria_id, -$this->cantidad);

			break;
			case self::TIPO_VENTA_EGRESO:

				//Multiplico por menos 1 porque el cantidad esta en negativa
				Bodegas::devolverABodega($this->categoria_id,$this->producto_id,$this->bodega_id,-$this->cantidad);

				ProductosTransacciones::transaccion(ProductosTransacciones::TIPO_VENTA_CANCELACION, $this->venta_producto_id, $this->bodega_id, $this->producto_id, $this->categoria_id, -$this->cantidad);

			break;
			default:
				throw new Exception("No se puede revertir esta transaccion");
		}
		return true;
	}

	/**
	$identificador = String con el nombre de columna donde esta el identificador del record_id
	$valor = el record_id
	**/
	public static function obtenerTransacciones ($identificador, $valor, $entrada =  null){

		$criteria=new CDbCriteria;
		$criteria->compare($identificador,$valor);
		if ($entrada !== null) {
			if ($entrada)
				$criteria->addCondition("SUBSTR(tipo,2,1) = '+' ");
			else
				$criteria->addCondition("SUBSTR(tipo,2,1) = '-' ");
		}

		return self::model()->findAll($criteria);
	}

	//Proceso en lote para regenerar referencias
	public static function refreshReferencias(){
		ini_set('memory_limit', '512M');
		$count = 0;
		$transacciones = self::model()->findAll();
		foreach($transacciones as $transaccion) {
			$transaccion->referencia = $transaccion->generarReferencia();
			$transaccion->save();
			$count++;
		}
		return $count;
	}
}