<?php

class ProductosController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

    public $hijos = [];

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
            'postOnly + addToCart', // we only allow deletion via POST request
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            ['allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => ['index','quickview', 'detail', 'search', 'categoria', 'addToCart', 'cart', 'removeItemFromCart', 'updateCantidad', 'exito', 'fail', 'exitomp', 'failmp', 'pendingmp', 'ipnmp', 'exitotp','failtp'],
                'users'   => ['*'],
            ],
            ['allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => ['checkout', 'todoPago', 'mercadoPago'],
                'users'   => ['@'],
            ],
            ['deny',  // deny all users
                'users' => ['*'],
            ],
        ];
    }

    public function actionQuickview($id1, $id2)
    {
        $producto = Productos::getProductInfo($id1, $id2);

        if ($producto === null)
          throw new CHttpException(404,'The requested page does not exist.');

        $this->renderPartial('quickview',[
            'producto'     => $producto,
            'categoria_id' => $id1,
            'producto_id'  => $id2,
            'center_cols'  => 8,
            'fancy'        => true
        ]);
    }

    public function actionDetail($id1, $id2)
    {
        $producto = Productos::getProductInfo($id1, $id2);

        if ($producto === null) {
            throw new CHttpException(404,'The requested page does not exist.');
        }

        $this->render('detail',array(
            'producto' => $producto,
            'categoria_id' => $id1,
            'producto_id' => $id2,
            'center_cols' => 5,
            'fancy' => false
        ));
    }

    public function actionCategoria($id)
    {
        $element = Categorias::model()->findByAttributes(
          [
            'categoria_id' => $id,
            'tiene_atributos' => 1
          ]);

        $categorias = [];
        $categoriasAux = [];
        array_push($categorias, $id);
        $categorias = array_merge($categorias, Categorias::getCategoriasFromCategoria($element, $id));
        array_unique($categorias);
        asort($categorias);

        if (count($categorias) == 0)
          throw new CHttpException(404, 'The requested page does not exist.');

        $modelo = $_GET['model'] ?: null;
        $marca = $_GET['marca'] ?: null;
        $productos = [];
        $productosAux = [];
        $modelos = [];
        $marcas = [];

        foreach ($categorias as $categoria){

            $productosAux = Productos::getProductosByCategoria($categoria,null,null,null,$marca,$modelo);

            $productos = array_merge($productos,$productosAux);
            
        }

        if ( $marca == null && $modelo == 0) {
            $marcas =   array_merge($marcas,ProductosMarcas::obtenerMarcasParaCategorias($categorias));
        }

        if ( $modelo == 0 && $marca != null) {
            $modelos =   array_merge($modelos,ProductosModelos::obtenerModelosParaCategoriasYMarca($categorias,$marca));
        }
          
        $page = $_GET['page'] ?: 1;
        $order = $_GET['order'] ?: Productos::VALUE_ORDER_PRICE_LOW;
        $pageSize = $_GET['pageSize'] ?: 100;
        $dataProvider = new CArrayDataProvider($productos,
            [
                'keyField'       => 'producto_id',
                'totalItemCount' => count($productos),
                'pagination'     => [
                    'pageSize'   => $pageSize,
                ],
            ]);

        // Obtener una lista de columnas
        foreach ($dataProvider->rawData as $clave => $fila) {
            $precioOnline[$clave] = $fila->precio[ProductosPrecios::PRECIO_ONLINE_ID]['precio'];
            $ahorro[$clave] = $fila->oferta[0]->producto->precio[ProductosPrecios::PRECIO_AHORRO_ID];
            $novedad[$clave] = $fila['novedad'];
            $isOferta[$clave] = $fila['isOferta'];
            $etiqueta[$clave] = $fila['etiqueta'];
            $fecha_creacion[$clave] = $fila['fecha_creacion'];
        }

        if (count($dataProvider->rawData)) {
            switch ($order) {
                case Productos::VALUE_ORDER_PRICE_LOW:
                    $labelOrder = Productos::LABEL_ORDER_PRICE_LOW;
                    array_multisort($precioOnline, SORT_ASC, SORT_NUMERIC, $etiqueta, SORT_ASC, SORT_STRING, $dataProvider->rawData);
                    break;
                case Productos::VALUE_ORDER_PRICE_HIGH:
                    $labelOrder = Productos::LABEL_ORDER_PRICE_HIGH;
                    array_multisort($precioOnline, SORT_DESC, SORT_NUMERIC, $etiqueta, SORT_ASC, SORT_STRING, $dataProvider->rawData);
                    break;
                case Productos::VALUE_ORDER_NAME_ASC:
                    $labelOrder = Productos::LABEL_ORDER_NAME_ASC;
                    array_multisort($etiqueta, SORT_ASC, SORT_STRING, $dataProvider->rawData);
                    break;
                case Productos::VALUE_ORDER_NAME_DESC:
                    $labelOrder = Productos::LABEL_ORDER_NAME_DESC;
                    array_multisort($etiqueta, SORT_DESC, SORT_STRING, $dataProvider->rawData);
                    break;
                case Productos::VALUE_ORDER_AHORRO_DESC:
                    $labelOrder = Productos::LABEL_ORDER_AHORRO_DESC;
                    array_multisort($ahorro, SORT_DESC, SORT_NUMERIC, $etiqueta, SORT_DESC, SORT_STRING, $dataProvider->rawData);
                    break;
                default:
                case Productos::VALUE_ORDER_LAST_CREATED:
                    $labelOrder = Productos::LABEL_ORDER_LAST_CREATED;
                    array_multisort($isOferta, SORT_DESC, SORT_NUMERIC, $etiqueta, SORT_DESC, SORT_STRING, $dataProvider->rawData);
                    break;
            }
        }

        $this->render('categoria',[
            'dataProvider' => $dataProvider,
            'pageSize'     => $pageSize,
            'page'         => $page,
            'order'        => $order,
            'id'           => $id,
            'action' => 'categoria',
            'labelOrder'   => $labelOrder,
            'modelos' => $modelos,
            'marcas' => $marcas
        ]);
    }

    public function actionAddToCart()
    {
        extract($_POST);

        //throw new CHttpException(500, 'No podes.');
        $cart = CarritoProductosWeb::addToCart($categoria_id, $producto_id, $cantidad);


        $items = [];
        if ($cart) {
            $items = CarritoProductosWeb::getCartItems();
        }

/*
        if ($cart && isset($incluirEnvio) && $incluirEnvio == "true")
            $items->total = $items->total + Commons::formatPrice(CarritoProductosWeb::calcularCostoEnvio());*/

        header("Content-type: application/json");
        echo CJSON::encode(
            [
                'ok'          => true,
                'producto_id' => $producto_id,
                'cart'        => $cart,
                'items'       => $items,
                'menuCart'    => Yii::app()->controller->renderFile(
                  Yii::app()->basePath.'/views/shared/_menuCart.php',
                  [
                    'cartItems' => $items
                  ],
                  true,
                  true
                ),
                'menuCartMobile' => Yii::app()->controller->renderFile(
                    Yii::app()->basePath.'/views/shared/_menuCartMobile.php',
                    [
                      'cartItems' => $items
                    ],
                    true,
                    true
                ),
                'total'      => Commons::formatPrice($items->total),
                'itemsCount' => $items->itemsCount
            ]);
        exit;
    }

    public function actionCart()
    {
        $items = CarritoProductosWeb::getCartItems();

        //Calculo costo de envio
        $costoEnvio = CarritoProductosWeb::calcularCostoEnvio();

        $this->render('cart',
            array(
                'items' => $items,
                'costoEnvio' => $costoEnvio
            )
        );
    }

    public function actionRemoveItemFromCart()
    {
        extract($_POST);

        CarritoProductosWeb::removeItemFromCart($categoria_id, $producto_id);

        $items = CarritoProductosWeb::getCartItems();

        header("Content-type: application/json");
        echo CJSON::encode(
            array(
                'ok' => true,
                'producto_id' => $producto_id,
                'items' => $items,
                'menuCart' => Yii::app()->controller->renderFile(
                    Yii::app()->basePath.'/views/shared/_menuCart.php',
                    array(
                        'cartItems' => $items
                    ),
                    true,
                    true
                ),
                'menuCartMobile' => Yii::app()->controller->renderFile(
                    Yii::app()->basePath.'/views/shared/_menuCartMobile.php',
                    array(
                        'cartItems' => $items
                    ),
                    true,
                    true
                ),
                'total' => Commons::formatPrice($items->total),
                'itemsCount' => $items->itemsCount
            )
        );
        exit;
    }

    public function actionUpdateCantidad()
    {
        extract($_POST);

        $producto = CarritoProductosWeb::productoInCart($categoria_id, $producto_id, Yii::app()->session['cart_web_clave'], Yii::app()->user->id);

        if ($producto !== null) {
            $cantidad_original = $producto->cantidad;
            $cantidad = $cantidad - $cantidad_original;
        }

        $cart = CarritoProductosWeb::addToCart($categoria_id, $producto_id, $cantidad);

        $items = CarritoProductosWeb::getCartItems();

        header("Content-type: application/json");
        echo CJSON::encode(
            [
                'ok' => true,
                'producto_id' => $producto_id,
                'cart' => $cart,
                'items' => $items,
                'menuCart' => Yii::app()->controller->renderFile(
                    Yii::app()->basePath.'/views/shared/_menuCart.php',
                    [
                        'cartItems' => $items
                    ],
                    true,
                    true
                ),
                'menuCartMobile' => Yii::app()->controller->renderFile(
                    Yii::app()->basePath.'/views/shared/_menuCartMobile.php',
                    [
                        'cartItems' => $items
                    ],
                    true,
                    true
                ),
                'total' => Commons::formatPrice($items['total']),
                'itemsCount' => $items['itemsCount']
            ]
        );
        exit;
    }

    public function actionCheckout()
    {
        extract($_POST);

        if (isset($_POST) && $items['itemsCount'] <= 0) {
        //    $this->redirect(Yii::app()->baseUrl);
        }

        $direcciones = ClientesDirecciones::getDireccionesByUser();

        if (isset($_POST['medio_pago'])) { // si se eligio alguna de las opciones se crea un nuevo pedido
          $pedido                        = new PedidoOnline;
          $pedido->id_medio_pago         = $_POST['medio_pago'];
          $pedido->direccion_facturacion = $_POST['facturacion_id'];
          $pedido->direccion_envio       = $_POST['entrega_id'];
          $pedido->forma_envio       = $_POST['formas_envio'];

          //Si el pedido es envio es retiro por sucursal entonces guardo la bodega
          if ( $_POST['formas_envio'] == PedidoOnline::FORMAENVIO_RETIROSUCURSAL)
            $pedido->bodega_id       = $_POST['bodegas'];
          
          

          $result = $pedido->cargarPedido();

          
          if(!$result['success']){
            if ($result['error'] = 'no_stock'){
                $items = CarritoProductosWeb::getCartItems();

                $this->render('cart',
                    array(
                        'items' => $items,
                        'no_stock_items' => $result['no_stock_items']
                    )
                );

            }

          if(!$result['success'])
            throw new CHttpException($result['msg']);
          }
          else {

            $fp = $pedido->getFormaPago();

            if ( $fp::isEmbebbedPayment() )
                $this->redirect($pedido->urlFormPago);
            else{
                $this->render('pending',
                    [
                      'message' => $fp::pendingMessage()
                    ]
                );
            }
          }
        }

        //Calculo costo de envio
        $costoEnvio = CarritoProductosWeb::calcularCostoEnvio();

        $this->render('checkout',
            [
              'items'       => CarritoProductosWeb::getCartItems(),
              'direcciones' => $direcciones,
              'costoEnvio' => $costoEnvio            ]
        );
    }

    public function actionExitotp()
    {
        $idOperacion = $_GET['operationid'];

        $TPC = new TodoPagoCallback($idOperacion);

        if($TPC->exito()) {
          $this->render('exito',['message' => $TPC->getMessages()]);
        } else {
          $this->render('fail',['message' => $TPC->getMessages()]);
        }
    }

    public function actionFailtp()
    {
      $idOperacion = $_GET['operationid'];

      $TPC = new TodoPagoCallback($idOperacion);

      $TPC->fail();

      $this->render('fail',['message' => $TPC->getMessages()]);
    }

    public function catchSeries($categoria_id, $producto_id, $cantidad)
    {
        $sql = "SELECT *
                FROM `numeros_serie_historico`
                WHERE categoria_id = " . $categoria_id . "
                AND producto_id = " . $producto_id . "
                AND compra_id != 0 AND venta_id = 0 AND estado = 0
                ORDER BY RAND()
                LIMIT " . $cantidad;
        return NumerosSerieHistorico::model()->findAllBySql($sql);
    }

    protected function _saveSeries($series, $venta_id, $categoria_id, $producto_id)
    {
        foreach($series as $serie){
            // chequeo previamente el numero de serie
            $serie = strtoupper($serie);
            $producto = Productos::getProductInfo($categoria_id, $producto_id);
            $nsh = new NumerosSerieHistorico();
            $nsh->categoria_id = $categoria_id;
            $nsh->producto_id = $producto_id;
            $nsh->numero_serie = $serie;
            $nsh->venta_id = $venta_id;
            $nsh->etiqueta = $producto->etiqueta;
            $nsh->save();
        }
    }

    public function actionExitomp()
    {
        //Proceso carrito (Fix por si aun no recibi notificacion IPN al volver)
        if (Yii::app()->session['pedido_id'])
            CarritoProductosWeb::setProcesado(Yii::app()->user->id,Yii::app()->session['pedido_id']);
        
        $this->render('exito',['message' => 'La venta ha sido procesada. ¡Felicidades!']);
    }

    public function actionFailmp()
    {
        unset(Yii::app()->session['pedido_id']);
        $this->render('fail',['message' => 'Ocurrió un error en el proceso de pago.']);
    }

    public function actionPendingmp()
    {

        $this->render('pending',
            [
              'message' => 'La venta se encuentra en estado Pendiente. Será notificado a la brevedad.'
            ]
        );
    }

    public function actionIpnmp()
    {
      $log = new LogData([
        'categoriaLog' => 'MercadoPago',
      ]);
      $log->toLog('Recibido verbo GET: '.json_encode($_GET));
      $log->toLog('Recibido verbo POST: '.json_encode($_POST));
      // Se deberia agregar una validacion de la IP de donde provienen las peticiones
      // correspondientes a los rangos de ip de la documentación de la API

      // corresponderia a un WebHook
      ////////////////////////////////////////////////
      if (isset($_GET['type'])){
        $json_event = file_get_contents('php://input', true);
        $event = json_decode($json_event);

        $WHData = new MercadoPagoWHData([
          'log'   => $log,
          'type'  => $_GET['type'],
          'event' => $event,
        ]);
        return $WHData->procesar();
      }

      // corresponderia a una IPN
      ////////////////////////////////////////////////
      if (isset($_GET['topic'])){
        $log->toLog('Entro en TOPIC ');
        $IPNData = new MercadoPagoIPNData([
          'topic' => $_GET['topic'],
          'id'    => $_GET['id'],
        ]);
        $log->toLog('Hice new de IPNData');
        $log->toLog($IPNData->procesar());
        return ;
      }

      $log->closeLog();
    }

    public function actionSearch()
    {
        extract($_GET);
        $categorias = Categorias::model()->findAllByAttributes(
            ['tiene_atributos' => 1]);

        if (count($categorias) == 0) {
            throw new CHttpException(404, 'La página solicitada no existe.');
        }

        /*$modelo = $_GET['model'] ?: null;
        $marca = $_GET['marca'] ?: null;*/

        $productos = [];
        $modelos = [];
        $marcas = [];
        $categorias_ids = [];

        foreach ($categorias as $categoria) {
            $aux = Productos::getProductosByCategoria($categoria->categoria_id, null, $keyword,null,$marca, $modelo);
            
            //Solo busco en esta categoria si la busqueda arrojo algun resultado para la misma
            if (sizeof($aux) > 0) {
                $categorias_ids[] = $categoria->categoria_id;
                $productos = array_merge($productos,$aux );
            }
        }

        //Omito marcas y modelos para busquedas
        /*
        if ( $marca != null && $modelo == null) {
            $modelos =   array_merge($modelos,ProductosModelos::obtenerModelosParaCategoriasYMarca($categorias_ids,$marca));
        }

        if ( $marca == null && $modelo == null) {
            $marcas =   array_merge($marcas,ProductosMarcas::obtenerMarcasParaCategorias($categorias_ids));
        }*/

        $page = $_GET['page'] ?: 1;
        $order = $_GET['order'] ?: 'ID';
        $pageSize = $_GET['pageSize'] ?: 100;
        $dataProvider = new CArrayDataProvider($productos,
            array(
                'keyField' => 'producto_id',
                'totalItemCount' => count($productos),
                'pagination' => array(
                    'pageSize' => $pageSize,
                ),
            ));

        // Obtener una lista de columnas
        foreach ($dataProvider->rawData as $clave => $fila) {
            //echo "<pre>"; var_dump($fila->oferta[0]->producto->precio[ProductosPrecios::$PRECIO_AHORRO_ID]); die;
            $precioOnline[$clave] = $fila->precio[ProductosPrecios::PRECIO_ONLINE_ID]['precio'];
            $ahorro[$clave] = $fila->oferta[0]->producto->precio[ProductosPrecios::PRECIO_AHORRO_ID];
            $novedad[$clave] = $fila['novedad'];
            $isOferta[$clave] = $fila['isOferta'];
            $etiqueta[$clave] = $fila['etiqueta'];
            $fecha_creacion[$clave] = $fila['fecha_creacion'];
        }

        if (count($dataProvider->rawData)) {
            switch ($order) {
                case Productos::VALUE_ORDER_PRICE_LOW:
                    $labelOrder = Productos::LABEL_ORDER_PRICE_LOW;
                    array_multisort($precioOnline, SORT_ASC, SORT_NUMERIC, $etiqueta, SORT_ASC, SORT_STRING, $dataProvider->rawData);
                    break;
                case Productos::VALUE_ORDER_PRICE_HIGH:
                    $labelOrder = Productos::LABEL_ORDER_PRICE_HIGH;
                    array_multisort($precioOnline, SORT_DESC, SORT_NUMERIC, $etiqueta, SORT_ASC, SORT_STRING, $dataProvider->rawData);
                    break;
                case Productos::VALUE_ORDER_NAME_ASC:
                    $labelOrder = Productos::LABEL_ORDER_NAME_ASC;
                    array_multisort($etiqueta, SORT_ASC, SORT_STRING, $dataProvider->rawData);
                    break;
                case Productos::VALUE_ORDER_NAME_DESC:
                    $labelOrder = Productos::LABEL_ORDER_NAME_DESC;
                    array_multisort($etiqueta, SORT_DESC, SORT_STRING, $dataProvider->rawData);
                    break;
                case Productos::VALUE_ORDER_AHORRO_DESC:
                    $labelOrder = Productos::LABEL_ORDER_AHORRO_DESC;
                    array_multisort($ahorro, SORT_DESC, SORT_NUMERIC, $etiqueta, SORT_DESC, SORT_STRING, $dataProvider->rawData);
                    break;
                default:
                case Productos::VALUE_ORDER_LAST_CREATED:
                    $labelOrder = Productos::LABEL_ORDER_LAST_CREATED;
                    array_multisort($isOferta, SORT_DESC, SORT_NUMERIC, $etiqueta, SORT_DESC, SORT_STRING, $dataProvider->rawData);
                    break;
            }
        }

        $this->render('categoria',[
            'dataProvider' => $dataProvider,
            'pageSize' => $pageSize,
            'page' => $page,
            'order' => $order,
            'id' => $id,
            'action' => 'search',
            'labelOrder' => $labelOrder,
            'modelos' => $modelos,
            'marcas' => $marcas
        ]);
    }

}
