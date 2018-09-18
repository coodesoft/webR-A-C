<?php

class ProductosController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

    public $hijos = array();

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + addToCart', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index','quickview', 'detail', 'search', 'categoria', 'addToCart', 'cart', 'removeItemFromCart', 'updateCantidad', 'exito', 'fail', 'exitomp', 'failmp', 'pendingmp', 'ipnmp'),
                'users'=>array('*'),
            ),
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('checkout', 'todoPago', 'mercadoPago'),
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionQuickview($id1, $id2)
    {
        $producto = Productos::getProductInfo($id1, $id2);

        if ($producto === null) {
            throw new CHttpException(404,'The requested page does not exist.');
        }

        $this->renderPartial('quickview',array(
            'producto' => $producto,
            'categoria_id' => $id1,
            'producto_id' => $id2,
            'center_cols' => 8,
            'fancy' => true
        ));
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
        array(
        'categoria_id' => $id,
        'tiene_atributos' => 1
        )
        );

        $categorias = [];
        array_push($categorias, $id);
        $categorias = array_merge($categorias, Categorias::getCategoriasFromCategoria($element, $id));
        array_unique($categorias);
        asort($categorias);

        //Commons::dump($categorias);

        //$categoria = Categorias::model()->findByPk($id);
        if (count($categorias) == 0) {
        throw new CHttpException(404, 'The requested page does not exist.');
        }

        $productos = [];
        foreach ($categorias as $categoria) {
        $productos = array_merge($productos, Productos::getProductosByCategoria($categoria));
        }
/*
        $categoria = Categorias::model()->findByPk($id);
        if ($categoria === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        $productos = Productos::getProductosByCategoria($categoria->categoria_id);
*/
        $page = $_GET['page'] ?: 1;
        $order = $_GET['order'] ?: Productos::VALUE_ORDER_PRICE_LOW;
        $pageSize = $_GET['pageSize'] ?: 15;
        $dataProvider = new CArrayDataProvider($productos,
            array(
                'keyField' => 'producto_id',
                'totalItemCount' => count($productos),
                'pagination' => array(
                    'pageSize' => $pageSize,
                ),
        ));

        //echo "<pre>"; var_dump($dataProvider->rawData); die;
        // Obtener una lista de columnas
        foreach ($dataProvider->rawData as $clave => $fila) {
            //echo "<pre>"; var_dump($fila->oferta[0]->producto->precio[ProductosPrecios::PRECIO_AHORRO_ID]); die;
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

        $this->render('categoria',array(
            'dataProvider' => $dataProvider,
            'pageSize' => $pageSize,
            'page' => $page,
            'order' => $order,
            'id' => $id,
            'labelOrder' => $labelOrder
        ));
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

        header("Content-type: application/json");
        echo CJSON::encode(
            array(
                'ok' => true,
                'producto_id' => $producto_id,
                'cart' => $cart,
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
                'total' => Commons::formatPrice($items['total']),
                'itemsCount' => $items['itemsCount']
            )
        );
        exit;
    }

    public function actionCart()
    {
        $items = CarritoProductosWeb::getCartItems();

        $this->render('cart',
            array(
                'items' => $items
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
                'total' => Commons::formatPrice($items['total']),
                'itemsCount' => $items['itemsCount']
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
            array(
                'ok' => true,
                'producto_id' => $producto_id,
                'cart' => $cart,
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
                'total' => Commons::formatPrice($items['total']),
                'itemsCount' => $items['itemsCount']
            )
        );
        exit;
    }

    public function actionCheckout()
    {
        extract($_POST);

        $items = CarritoProductosWeb::getCartItems();

        if ($items['itemsCount'] <= 0) {
            $this->redirect(Yii::app()->baseUrl);
        }

        $plataformas = ConfiguracionesWeb::getConfigWeb();
        $direcciones = ClientesDirecciones::getDireccionesByUser();

        if (isset($_POST['optionsRadios'])) {

            $mailSentWithNoProducts = false;
            if (!isset($_POST['consentCompraWithoutStock']) && $_POST['consentCompraWithoutStock'] == 0) {
                foreach ($items as $item) {
                    //Commons::dump($item->producto->attributes);
                    if (isset($item->producto) && ($item->producto['stock'] - $item['cantidad'] <= 0)) {
                        $mail = new EnviarMail();
                        $emailTo = $plataformas->email_recibe_notificaciones_ventas;
                        $subject = "Alerta de producto sin stock en compra por web";
                        $pbody = 'Administrador, <br><br>El siguiente producto fue pedido pero no hay stock:';
                        $pbody .= '<br><br>';
                        $pbody .= 'Código: ' . $item->producto['codigo'];
                        $pbody .= '<br>';
                        $pbody .= 'Producto: ' . $item->producto['etiqueta'];
                        $pbody .= '<br><br>';
                        $pbody .= 'Cliente';
                        $pbody .= '<br>';
                        $cliente = Clientes::model()->findByAttributes(
                            array(
                                'user_id' => Yii::app()->user->id
                            )
                        );
                        $pbody .= 'Apellido y nombre: ' . $cliente->apellidoYNombre;
                        $pbody .= '<br>';
                        $pbody .= 'Teléfono: ' . $direcciones[0]->telefono;
                        $pbody .= '<br>';
                        $pbody .= 'Email: ' . $cliente->email;
                        $pbody .= '<br><br>';
                        $pbody .= '<br><br>';

                        $mail->enviarEmail(Yii::app()->params['adminEmail'], $emailTo, $subject, $pbody);

                        $mailSentWithNoProducts = true;
                    }
                }
            }

            if (!$mailSentWithNoProducts) {
                switch ($_POST['optionsRadios']) {
                    case 'tp':
                        if ($plataformas->todo_pago_enabled) {
                            $this->redirect(array('productos/todoPago?entrega_id=' . $entrega_id . '&facturacion_id=' . $facturacion_id));
                        }
                        break;
                    case 'mp':
                        if ($plataformas->mercado_pago_enabled) {
                            $this->redirect(array('productos/mercadoPago?entrega_id=' . $entrega_id . '&facturacion_id=' . $facturacion_id));
                        }
                        break;
                }
            } else {
                Yii::app()->user->setFlash('error','');
            }
        }

        $this->render('checkout',
            array(
                'items' => $items,
                'mpEnabled' => $plataformas->mercado_pago_enabled,
                'tpEnabled' => $plataformas->todo_pago_enabled,
                'direcciones' => $direcciones
            )
        );
    }

    public function actionTodoPago()
    {
        extract($_GET);

        if (strpos($_SERVER['HTTP_REFERER'], Yii::app()->baseUrl . '/productos/checkout') == false) {
            $this->redirect(array('productos/checkout/'));
        }

        $this->layout = '//layouts/noLayout';

        require_once Yii::app()->basePath . '/extensions/todoPago/lib/Sdk.php';
        require_once Yii::app()->basePath . '/extensions/todoPago/lib/Client.php';

        try {
            $dirFacturacion = ClientesDirecciones::findBy($facturacion_id);
            $dirEntrega = ClientesDirecciones::findBy($entrega_id);
            $user = User::model()->findByPk(Yii::app()->user->id);
        } catch (Exception $e) {
            $this->redirect(array('productos/checkout/'));
        }

        $tp = new TodoPago();

        $items = CarritoProductosWeb::getCartItems();
        $form = $tp->prepareForm($items);

        //id de la operacion
        $tp->operationId = rand(0, 99999999);

        $http_header = array(
            'Authorization' => $tp->authKey,
            'user_agent' => 'PHPSoapClient',
        );

        //opciones para el método sendAuthorizeRequest
        $optionsSAR_comercio = array(
            'Security' => $tp->security,
            'EncodingMethod' => 'XML',
            'Merchant' => $tp->merchant,
            'URL_OK' => TodoPago::URL_OK . $tp->operationId,
            'URL_ERROR' => TodoPago::URL_KO . $tp->operationId
        );

        $optionsSAR_operacion = array(
            'MERCHANT'=> $tp->merchant,
            'OPERATIONID'=> $tp->operationId,
            'CURRENCYCODE'=> 26,
            'AMOUNT'=> $form['total'],
            //Datos ejemplos CS
            'CSBTCITY'=> $dirFacturacion->ciudad->ciudad, //Ciudad de facturación, MANDATORIO.
            'CSSTCITY'=> $dirEntrega->ciudad->ciudad, //Ciudad de envío de la orden. MANDATORIO.

            'CSBTCOUNTRY'=> "AR", //País de facturación. MANDATORIO. Código ISO. (http://apps.cybersource.com/library/documentation/sbc/quickref/countries_alpha_list.pdf)
            'CSSTCOUNTRY'=> "AR", //País de envío de la orden. MANDATORIO.

            'CSBTEMAIL'=> $user->email, //Mail del usuario al que se le emite la factura. MANDATORIO.
            'CSSTEMAIL'=> $user->email, //Mail del destinatario, MANDATORIO.

            'CSBTFIRSTNAME'=> $dirFacturacion->nombre_destinatario, //Nombre del usuario al que se le emite la factura. MANDATORIO.
            'CSSTFIRSTNAME'=> $dirEntrega->nombre_destinatario, //Nombre del destinatario. MANDATORIO.

            'CSBTLASTNAME'=> $dirFacturacion->apellido_destinatario, //Apellido del usuario al que se le emite la factura. MANDATORIO.
            'CSSTLASTNAME'=> $dirEntrega->apellido_destinatario, //Apellido del destinatario. MANDATORIO.

            'CSBTPHONENUMBER'=> $dirFacturacion->telefono, //Teléfono del usuario al que se le emite la factura. No utilizar guiones, puntos o espacios. Incluir código de país. MANDATORIO.
            'CSSTPHONENUMBER'=> $dirEntrega->telefono, //Número de teléfono del destinatario. MANDATORIO.

            'CSBTPOSTALCODE'=> $dirFacturacion->cpostal, //Código Postal de la dirección de facturación. MANDATORIO.
            'CSSTPOSTALCODE'=> $dirEntrega->cpostal, //Código postal del domicilio de envío. MANDATORIO.

            'CSBTSTATE'=> $dirFacturacion->provincia->codigoTP, //Provincia de la dirección de facturación. MANDATORIO. Ver tabla anexa de provincias.
            'CSSTSTATE'=> $dirEntrega->provincia->codigoTP, //Provincia de envío. MANDATORIO. Son de 1 caracter

            'CSBTSTREET1'=> $dirFacturacion->fullDomicilio, //Domicilio de facturación (calle y nro). MANDATORIO.
            'CSSTSTREET1'=> $dirEntrega->fullDomicilio, //Domicilio de envío. MANDATORIO.

            'CSBTCUSTOMERID'=> Yii::app()->user->id, //Identificador del usuario al que se le emite la factura. MANDATORIO. No puede contener un correo electrónico.
            'CSBTIPADDRESS'=> Commons::getUserIP(), //IP de la PC del comprador. MANDATORIO.
            'CSPTCURRENCY'=> "ARS", //Moneda. MANDATORIO.
            'CSPTGRANDTOTALAMOUNT'=> VentasTodoPago::formatPrice($items['total']), //Con decimales opcional usando el punto como separador de decimales. No se permiten comas, ni como separador de miles ni como separador de decimales. MANDATORIO.

            /*
            'CSMDD8'=> "Y", //Usuario Guest? (Y/N). En caso de ser Y, el campo CSMDD9 no deberá enviarse. NO MANDATORIO.
            'CSMDD7'=> "", // Fecha registro comprador(num Dias). NO MANDATORIO.
            'CSMDD9'=> "", //Customer password Hash: criptograma asociado al password del comprador final. NO MANDATORIO.
            'CSMDD10'=> "", //Histórica de compras del comprador (Num transacciones). NO MANDATORIO.
            'CSMDD11'=> "", //Customer Cell Phone. NO MANDATORIO.
            'CSMDD12'=> "", //Shipping DeadLine (Num Dias). NO MADATORIO.
            'CSMDD13'=> "", //Método de Despacho. NO MANDATORIO.
            'CSMDD14'=> "", //Customer requires Tax Bill ? (Y/N). NO MANDATORIO.
            'CSMDD15'=> "", //Customer Loyality Number. NO MANDATORIO.
            'CSMDD16'=> "", //Promotional / Coupon Code. NO MANDATORIO.
            */

            'CSITPRODUCTCODE'=> $form['code'], //Código de producto. CONDICIONAL. Valores posibles(adult_content;coupon;default;electronic_good;electronic_software;gift_certificate;handling_only;service;shipping_and_handling;shipping_only;subscription)
            'CSITPRODUCTDESCRIPTION'=> $form['etiquetas'], //Descripción del producto. CONDICIONAL.
            'CSITPRODUCTNAME'=> $form['etiquetas'], //Nombre del producto. CONDICIONAL.
            'CSITPRODUCTSKU'=> $form['ids'], //Código identificador del producto. CONDICIONAL.
            'CSITTOTALAMOUNT'=> $form['unitarios'], //CSITTOTALAMOUNT=CSITUNITPRICE*CSITQUANTITY "999999[.CC]" Con decimales opcional usando el punto como separador de decimales. No se permiten comas, ni como separador de miles ni como separador de decimales. CONDICIONAL.
            'CSITQUANTITY'=> $form['cantidades'], //Cantidad del producto. CONDICIONAL.
            'CSITUNITPRICE'=> $form['unitarios'], //Formato Idem CSITTOTALAMOUNT. CONDICIONAL.
        );

        //creo instancia de la clase TodoPago
        $connector = new Sdk($http_header, TodoPago::MODE);
        $rta = $connector->sendAuthorizeRequest($optionsSAR_comercio, $optionsSAR_operacion);
        Yii::app()->session['RequestKey'] = $rta['RequestKey'];

        if ($rta['StatusCode'] == -1) {
            $vtp = new VentasTodoPago();
            $vtp->attributes = array_change_key_case($optionsSAR_operacion, CASE_LOWER);
            $vtp->user_id = Yii::app()->user->id;
            $vtp->estado_id = VentasTodoPago::STATUS_INCOMPLETE;
            $vtp->fecha = date('Y-m-d H:i:s');
            $vtp->save(false);

            $this->redirect($rta['URL_Request']);
        }

        $this->redirect(Yii::app()->baseUrl . '/productos/checkout');
    }

    public function actionExito()
    {
        require_once Yii::app()->basePath . '/extensions/todoPago/lib/Sdk.php';

        $rta2 = TodoPago::getResponse();

        if ($rta2['StatusCode'] == -1) {
            $operationid = $_GET['operationid'];
            VentasTodoPago::updateStatus($operationid, VentasTodoPago::STATUS_PENDING);

            $vtps = VentasTodoPagoStatus::model()->findByAttributes(
                array(
                    'operationid' => $operationid
                )
            );

            if ($vtps === null) {
                $vtps = new VentasTodoPagoStatus();
                $vtps->operationid = $operationid;
                $vtps->statusCode = $rta2['StatusCode'];
                $vtps->statusMessage = $rta2['StatusMessage'];
                $vtps->authorizationKey = $rta2['AuthorizationKey'];
                $vtps->encodingMethod = $rta2['EncodingMethod'];
                $vtps->payload = CJSON::encode($rta2['Payload']);
                $vtps->save();

                //Commons::dump($vtps->attributes);
                CarritoProductosWeb::dropUserCartAfterProcess();
                $this->pedidoToVentaTp($operationid);

                $this->render('exito',
                    array(
                        'message' => 'La venta ha sido procesada. Felicidades!'
                    )
                );
            } else {
                $this->render('fail',
                    array(
                        'message' => 'La compra ya fue procesada.'
                    )
                );
            }
        } else {
            $this->render('fail',
                array(
                    'message' => 'Error en el proceso. Vuelva a intentar.'
                )
            );
        }
    }

    public function pedidoToVentaTp($operationid)
    {
        $v = VentasTodoPago::getPedido($operationid);

        $cliente = Clientes::model()->findByAttributes(
            array(
                'user_id' => $v->user_id
            )
        );

        $venta = new Ventas();
        $venta->cliente_id = $cliente->cliente_id;
        $venta->bodega_id = Bodegas::getBodegaPredeterminada();
        $venta->tipo_venta_id = 1;
        $venta->fecha = date('Y-m-d H:i:s');
        $venta->total = $v->csptgrandtotalamount;
        $venta->referencia = Ventas::getNextReferencia();
        $venta->estado_venta_id = 1;
        $venta->costo_envio = 0;
        $venta->descuento = 0;
        $venta->impuesto = 0;
        $venta->user_id = 0;
        $venta->clave = SlugGen::readableSlug(20);
        if ($venta->save()) {
            $prodsSkus = explode('#', $v->csitproductsku);
            foreach ($prodsSkus as $key => $prod) {
                $e = explode('_', $prod);
                $categoria_id = $e[0];
                $producto_id = $e[1];
                $cantidad = explode('#', $v->csitquantity);
                $unitario = explode('#', $v->csitunitprice);
                $totalRow = explode('#', $v->csittotalamount);

                $ventaProducto = new VentasProductos();
                $ventaProducto->venta_id = $venta->venta_id;
                $ventaProducto->categoria_id = $categoria_id;
                $ventaProducto->producto_id = $producto_id;
                $ventaProducto->cantidad = $cantidad[$key];
                $ventaProducto->precio_unitario = $unitario[$key];
                $ventaProducto->descuento = 0;
                $ventaProducto->garantia = 6;
                $ventaProducto->precio_total = $totalRow[$key];
                $ventaProducto->precio_id = ProductosPrecios::PRECIO_ONLINE_ID;
                if ($ventaProducto->save()) {
                    // actualizo el stock del producto
                    $ps = ProductosStock::model()->findByAttributes(array('categoria_id'=>$categoria_id,'producto_id'=>$producto_id,'bodega_id'=>$venta->bodega_id));
                    $ps->cantidad -= $cantidad[$key];
                    $ps->save();

                    $categoria = Categorias::model()->finbByPk($categoria_id);
                    if ($categoria->tiene_numeros_series == 1) {
                        $series = $this->catchSeries($categoria_id, $producto_id, $cantidad[$key]);
                        $this->_saveSeries($series, $venta->venta_id, $categoria_id, $producto_id);
                    }
                }
            }

            $vfp = new VentasFormasPagos();
            $vfp->venta_id = $venta->venta_id;
            $vfp->forma_pago_id = 6;
            $vfp->monto = $v->csptgrandtotalamount;
            $vfp->observacion = '';
            $vfp->po_carro = 'Todo Pago';
            $vfp->po_tipo_tarjeta = 0;
            $vfp->po_cuotas = 0;
            $vfp->save(false);

            VentasTodoPago::updateStatus($operationid, VentasTodoPago::STATUS_OK);
        }
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

    public function actionFail()
    {
        require_once Yii::app()->basePath . '/extensions/todoPago/lib/Sdk.php';

        $rta2 = TodoPago::getResponse();

        $operationid = $_GET['operationid'];
        VentasTodoPago::updateStatus($operationid, VentasTodoPago::STATUS_ERROR);

        $vtps = new VentasTodoPagoStatus();
        $vtps->operationid = $operationid;
        $vtps->statusCode = $rta2['StatusCode'];
        $vtps->statusMessage = $rta2['StatusMessage'];
        $vtps->authorizationKey = $rta2['AuthorizationKey'];
        $vtps->encodingMethod = $rta2['EncodingMethod'];
        $vtps->payload = CJSON::encode($rta2['Payload']);
        $vtps->save();

        //Commons::dump($vtps->attributes);

        $this->render('fail',
            array(
                'message' => $rta2['StatusMessage']
            )
        );
    }

    public function actionMercadoPago()
    {
        extract($_GET);

        if (strpos($_SERVER['HTTP_REFERER'], Yii::app()->baseUrl . '/productos/checkout') == false) {
            $this->redirect(array('productos/checkout/'));
        }

        $this->layout = '//layouts/noLayout';

        try {
            $dirFacturacion = ClientesDirecciones::findBy($facturacion_id);
            $dirEntrega = ClientesDirecciones::findBy($entrega_id);
            $user = User::model()->findByPk(Yii::app()->user->id);
        } catch (Exception $e) {
            $this->redirect(array('productos/checkout/'));
        }

        require_once Yii::app()->basePath . '/extensions/mercadoPago/lib/mercadopago.php';

        $mp = new MP(MercadoPago::CLIENT_ID, MercadoPago::SECRET_ID);
        $accessToken = $mp->get_access_token();
        $mp->sandbox_mode(MercadoPago::SANDBOX_MODE);

        $mpModel = new MercadoPago();
        $items = CarritoProductosWeb::getCartItems();
        $form = $mpModel->prepareForm($items);

        //https://www.mercadopago.com.ar/developers/es/api-docs/basic-checkout/checkout-preferences/
        $preference_data = array(
            "items" => $form,
            "payer" => array(
                "name" => $user->profile->firstname,
                "surname" => $user->profile->lastname,
                "email" => $user->email,
                "phone" => array(
                    "area_code" => $dirFacturacion->prefijo,
                    "number" => $dirFacturacion->telefono
                ),
                "identification" => array(
                    "type" => $dirFacturacion->dni_tipo,
                    "number" => $dirFacturacion->dni
                ),
                "address" => array(
                    "zip_code" => $dirFacturacion->cpostal,
                    "street_name" => $dirFacturacion->domicilio,
                    "street_number" => $dirFacturacion->numero,
                )
            ),
            "shipments" => array(
                "mode" => "not_specified",
                "free_shipping" => true,
                "receiver_address" => array(
                    "zip_code" => $dirEntrega->cpostal,
                    "street_name" => $dirEntrega->domicilio,
                    "street_number" => $dirEntrega->numero,
                    "floor" => $dirEntrega->piso ? $dirEntrega->piso : '',
                    "apartment" => $dirEntrega->depto ? $dirEntrega->depto : ''
                ),
            ),
            "back_urls" => array(
                "success" => MercadoPago::URL_OK,
                "failure" => MercadoPago::URL_KO,
                "pending" => MercadoPago::URL_PEND
            ),
            "notification_url" => MercadoPago::URL_IPN,
            "external_reference" => rand(0, 99999999),
            "auto_return" => "all"
        );
        $preference = $mp->create_preference($preference_data);

        if ($preference['status'] == 201) {

            $vmp = new VentasMercadoPago();
            $vmp->user_id = Yii::app()->user->id;
            $vmp->estado_id = VentasMercadoPago::STATUS_INCOMPLETE;
            $vmp->date_created = date('Y-m-d H:i:s');
            $vmp->external_reference = $preference['response']['external_reference'];
            $vmp->preference_id = $preference['response']['id'];
            $vmp->collector_id = $preference['response']['collector_id'];
            $vmp->operation_type = $preference['response']['operation_type'];
            $vmp->payer_name = $preference['response']['payer']['name'];
            $vmp->payer_surname = $preference['response']['payer']['surname'];
            $vmp->payer_email = $preference['response']['payer']['email'];
            $vmp->payer_date_created = $preference['response']['payer']['date_created'];
            $vmp->payer_phone_area_code = $preference['response']['payer']['phone']['area_code'];
            $vmp->payer_phone_number = $preference['response']['payer']['phone']['number'];
            $vmp->payer_identification_type = $preference['response']['payer']['identification']['type'];
            $vmp->payer_identification_number = $preference['response']['payer']['identification']['number'];
            $vmp->payer_address_street_name = $preference['response']['payer']['address']['street_name'];
            $vmp->payer_address_street_number = $preference['response']['payer']['address']['street_number'];
            $vmp->payer_address_zip_code = $preference['response']['payer']['address']['zip_code'];
            $vmp->client_id = $preference['response']['client_id'];
            $vmp->shipments_free_shipping = $preference['response']['shipments']['free_shipping'] == true ? 1 : 0;
            $vmp->shipments_cost = $preference['response']['shipments']['cost'];
            $vmp->shipments_mode = $preference['response']['shipments']['mode'];
            $vmp->shipments_receiver_address_zip_code = $preference['response']['shipments']['receiver_address']['zip_code'];
            $vmp->shipments_receiver_address_street_name = $preference['response']['shipments']['receiver_address']['street_name'];
            $vmp->shipments_receiver_address_street_number = $preference['response']['shipments']['receiver_address']['street_number'];
            $vmp->shipments_receiver_address_floor = $preference['response']['shipments']['receiver_address']['floor'];
            $vmp->shipments_receiver_address_apartment = $preference['response']['shipments']['receiver_address']['apartment'];
            $vmp->collection_id = '';
            $vmp->collection_status = '';
            $vmp->payment_type = '';
            $vmp->merchant_order_id = '';
            if ($vmp->save(false)) {
                foreach ($preference['response']['items'] as $item) {
                    $vmpi = new VentasMercadoPagoItems();
                    $vmpi->venta_mercado_pago_id = $vmp->venta_mercado_pago_id;
                    $vmpi->producto_id = $item['id'];
                    $vmpi->category_id = $item['category_id'];
                    $vmpi->title = $item['title'];
                    $vmpi->description = $item['description'];
                    $vmpi->picture_url = $item['picture_url'];
                    $vmpi->currency_id = $item['currency_id'];
                    $vmpi->quantity = $item['quantity'];
                    $vmpi->unit_price = $item['unit_price'];
                    $vmpi->save(false);
                }
            }

            $this->redirect($preference['response'][$mpModel->process_url_key]);
        }

    }

    public function actionExitomp()
    {
        extract($_GET);
        require_once Yii::app()->basePath . '/extensions/mercadoPago/lib/mercadopago.php';

        $vmp = VentasMercadoPago::model()->findByAttributes(
            array(
                'preference_id' => $preference_id,
                'user_id' => Yii::app()->user->id,
                'external_reference' => $external_reference,
            )
        );

        if ($vmp !== null) {
            $vmp->estado_id = VentasMercadoPago::STATUS_PENDING;
            $vmp->collection_id = $collection_id;
            $vmp->collection_status = $collection_status;
            $vmp->payment_type = $payment_type;
            $vmp->merchant_order_id = $merchant_order_id;
            $vmp->save(false);

            CarritoProductosWeb::dropUserCartAfterProcess();
            $this->pedidoToVentaMp($preference_id, $external_reference);

            $this->render('exito',
                array(
                    'message' => 'La venta ha sido procesada. Felicidades!'
                )
            );
        } else {
            $this->render('fail',
                array(
                    'message' => 'Error en el proceso. Vuelva a intentar.'
                )
            );
        }

    }

    public function pedidoToVentaMp($preference_id, $external_reference)
    {
        $v = VentasMercadoPago::model()->findByAttributes(
            array(
                'preference_id' => $preference_id,
                'user_id' => Yii::app()->user->id,
                'external_reference' => $external_reference,
            )
        );

        $cliente = Clientes::model()->findByAttributes(
            array(
                'user_id' => $v->user_id
            )
        );

        $venta = new Ventas();
        $venta->cliente_id = $cliente->cliente_id;
        $venta->bodega_id = Bodegas::getBodegaPredeterminada();
        $venta->tipo_venta_id = 1;
        $venta->fecha = date('Y-m-d H:i:s');
        $venta->total = 1; // despues lo actualizo
        $venta->referencia = Ventas::getNextReferencia();
        $venta->estado_venta_id = 1;
        $venta->costo_envio = 0;
        $venta->descuento = 0;
        $venta->impuesto = 0;
        $venta->user_id = 0;
        $venta->clave = SlugGen::readableSlug(20);
        if ($venta->save()) {
            $prods = VentasMercadoPagoItems::getItemsFromVentaMp($v->venta_mercado_pago_id);
            $total = 0;
            foreach ($prods as $key => $prod) {
                $categoria_id = $prod->categoria_id;
                $producto_id = $prod->producto_id;
                $cantidad = $prod->quantity;
                $unitario = $prod->unit_price;
                $totalRow = $cantidad * $unitario;
                $total += $totalRow;

                $ventaProducto = new VentasProductos();
                $ventaProducto->venta_id = $venta->venta_id;
                $ventaProducto->categoria_id = $categoria_id;
                $ventaProducto->producto_id = $producto_id;
                $ventaProducto->cantidad = $cantidad;
                $ventaProducto->precio_unitario = $unitario;
                $ventaProducto->descuento = 0;
                $ventaProducto->garantia = 6;
                $ventaProducto->precio_total = $totalRow;
                $ventaProducto->precio_id = ProductosPrecios::PRECIO_ONLINE_ID;
                if ($ventaProducto->save()) {
                    // actualizo el stock del producto
                    $ps = ProductosStock::model()->findByAttributes(array('categoria_id'=>$categoria_id,'producto_id'=>$producto_id,'bodega_id'=>$venta->bodega_id));
                    $ps->cantidad -= $cantidad;
                    $ps->save();

                    $categoria = Categorias::model()->finbByPk($categoria_id);
                    if ($categoria->tiene_numeros_series == 1) {
                        $series = $this->catchSeries($categoria_id, $producto_id, $cantidad);
                        $this->_saveSeries($series, $venta->venta_id, $categoria_id, $producto_id);
                    }
                }
            }

            $venta->total = $total;
            $venta->save(); // actualizo el total de la venta

            $vfp = new VentasFormasPagos();
            $vfp->venta_id = $venta->venta_id;
            $vfp->forma_pago_id = 6;
            $vfp->monto = $total;
            $vfp->observacion = '';
            $vfp->po_carro = 'Mercado Pago';
            $vfp->po_tipo_tarjeta = 0;
            $vfp->po_cuotas = 0;
            $vfp->save(false);

            VentasMercadoPago::updateStatus($external_reference, VentasMercadoPago::STATUS_OK);
        }
    }

    public function actionFailmp()
    {
        extract($_GET);

        require_once Yii::app()->basePath . '/extensions/mercadoPago/lib/mercadopago.php';

        $vmp = VentasMercadoPago::model()->findByAttributes(
            array(
                'preference_id' => $preference_id,
                'user_id' => Yii::app()->user->id,
                'external_reference' => $external_reference
            )
        );

        if ($vmp !== null) {
            $vmp->estado_id = VentasMercadoPago::STATUS_ERROR;
            $vmp->collection_id = $collection_id;
            $vmp->collection_status = $collection_status;
            $vmp->payment_type = $payment_type;
            $vmp->merchant_order_id = $merchant_order_id;
            $vmp->save(false);
        }

        $this->render('fail',
            array(
                'message' => 'Error en el proceso. Vuelva a intentar.'
            )
        );
    }

    public function actionPendingmp()
    {
        extract($_GET);

        require_once Yii::app()->basePath . '/extensions/mercadoPago/lib/mercadopago.php';

        $vmp = VentasMercadoPago::model()->findByAttributes(
            array(
                'preference_id' => $preference_id,
                'user_id' => Yii::app()->user->id,
                'external_reference' => $external_reference
            )
        );

        if ($vmp !== null) {
            $vmp->estado_id = VentasMercadoPago::STATUS_PENDING;
            $vmp->collection_id = $collection_id;
            $vmp->collection_status = $collection_status;
            $vmp->payment_type = $payment_type;
            $vmp->merchant_order_id = $merchant_order_id;
            $vmp->save(false);
        }

        $this->render('pending',
            array(
                'message' => 'La venta se encuenta en estado Pendiente. Será notificado a la brevedad.'
            )
        );
    }

    public function actionIpnmp()
    {
        Commons::dump("IPN page", $_GET);
    }

    public function actionSearch()
    {
        extract($_GET);

        $categorias = Categorias::model()->findAllByAttributes(
            array(
                'tiene_atributos' => 1
            )
        );

        if (count($categorias) == 0) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        $productos = [];
        foreach ($categorias as $categoria) {
            $productos = array_merge($productos, Productos::getProductosByCategoria($categoria->categoria_id, null, $keyword));
        }

        $page = $_GET['page'] ?: 1;
        $order = $_GET['order'] ?: 'ID';
        $pageSize = $_GET['pageSize'] ?: 15;
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
            //echo "<pre>"; var_dump($fila->oferta[0]->producto->precio[ProductosPrecios::PRECIO_AHORRO_ID]); die;
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

        $this->render('categoria',array(
            'dataProvider' => $dataProvider,
            'pageSize' => $pageSize,
            'page' => $page,
            'order' => $order,
            'id' => $id,
            'labelOrder' => $labelOrder
        ));
    }

}
