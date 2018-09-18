<?php
/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 05/07/16
 * Time: 23:51
 */
$this->pageTitle = Yii::app()->name . ' :: ' . 'Checkout';
?>
<!-- Breadcrumbs -->
<section class="container">
    <nav class="breadcrumbs">
        <a href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">Home</a>
        <span class="divider">›</span> Checkout
    </nav>
</section>
<!-- //end Breadcrumbs -->

<!-- Two column content -->
<section class="container">
    <div class="row">
        <section class="col-md-7 col-lg-7">
            <!-- Checkout -->
            <section class="content-box">
                <div class="checkout">
                    <div class="box">
                        <div class="checkout_detail">
                            <form role="form" method="POST">
                                <h3>REALIZAR PAGO</h3>

                                <?php if(Yii::app()->user->hasFlash('error')): ?>
                                    <div class="alert alert-danger">
                                        <p>No hay stock de alguno de los productos seleccionados. Se ha enviado un email automático a un administrador para que se ponga en contacto contigo.</p>
                                        <p>
                                            <input type="checkbox" name="consentCompraWithoutStock" id="consentCompraWithoutStock" value="0" />
                                            <label for="consentCompraWithoutStock">
                                                <strong>Entiendo los riesgos, quiero continuar la compra.</strong>
                                            </label>
                                        </p>
                                    </div>
                                <?php endif; ?>
                                <div class="panel-group"  id="checkOut">
                                    <div class="panel panel-default">
                                        <div class="panel-heading  active">
                                            <h4 class="panel-title"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> <span>1</span>FORMA DE PAGO </a> </h4>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse in">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <?php if ($mpEnabled) { ?>
                                                            <div class="radio">
                                                                <label>
                                                                    <input type="radio" checked="" value="mp" id="optionsRadios1" name="optionsRadios">
                                                                    MercadoPago
                                                                </label>
                                                                <a href="https://www.mercadopago.com.ar/promociones" data-fancybox-type="iframe" class="fancybox fancyMp">(ver promos)</a>
                                                                <div>
                                                                    <img width="80" src="<?php echo Yii::app()->theme->baseUrl ?>/images/icon-payment-02.png">
                                                                </div>
                                                                <p class="checkout_notes">En el siguiente paso se abrirá una ventana segura de Mercado Pago en donde podrás completar los detalles del pago.</p>
                                                            </div>
                                                        <?php } ?>

                                                        <?php if ($tpEnabled) { ?>
                                                            <div class="radio">
                                                                <label>
                                                                    <input type="radio" checked="" value="tp" id="optionsRadios1" name="optionsRadios">
                                                                    TodoPago
                                                                </label>
                                                                <a href="http://www.todopago.com.ar/promociones-vigentes" target="_blank">(ver promos)</a>
                                                                <div>
                                                                    <img width="80" src="<?php echo Yii::app()->theme->baseUrl ?>/images/icon-payment-01.png">
                                                                </div>
                                                                <p class="checkout_notes">Al continuar con la compra, estarás ingresando a un sitio seguro de Todo Pago.</p>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"> <span>2</span>DATOS DE FACTURACIÓN </a> </h4>
                                        </div>
                                        <div id="collapseTwo" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <?php
                                                    if (count($direcciones) != 0) {
                                                        ?>
                                                        <div class="col-sm-6">
                                                            <p>
                                                                <strong>
                                                                    <span class="facturacion_identificacion"><?php echo $direcciones[0]->identificacion; ?></span>
                                                                </strong>
                                                            </p>
                                                            <p>
                                                                <span class="facturacion_fullname"><?php echo $direcciones[0]->fullName; ?></span>
                                                            </p>
                                                            <p>
                                                                <span class="facturacion_domicilio"><?php echo $direcciones[0]->fullDomicilio; ?></span>
                                                            </p>
                                                            <p>
                                                                <span class="facturacion_ciudad"><?php echo $direcciones[0]->ciudad->ciudad; ?></span>
                                                            </p>
                                                            <p>
                                                                <span class="facturacion_provincia"><?php echo $direcciones[0]->provincia->provincia; ?></span>, <span class="facturacion_postal"><?php echo $direcciones[0]->cpostal; ?></span>
                                                            </p>
                                                            <input type="hidden" value="<?php echo $direcciones[0]->cliente_direccion_id ?>" class="facturacion_id" name="facturacion_id" />
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <h6>Seleccionar otra dirección</h6>

                                                            <section class="slider-products module">
                                                                <div class="products-widget jcarousel-skin-previews vertical">
                                                                    <ul class="slides">
                                                                        <?php
                                                                        foreach ($direcciones as $direccion) {
                                                                            ?>
                                                                            <li>
                                                                                <div class="product">
                                                                                    <div class="pull-left">
                                                                                        <p>
                                                                                            <strong>
                                                                                                <span><?php echo $direccion->identificacion; ?>, <span><?php echo $direccion->fullName; ?></span></span>
                                                                                            </strong>
                                                                                        </p>
                                                                                        <p>
                                                                                            <span><?php echo $direccion->fullDomicilio; ?></span>
                                                                                        </p>
                                                                                        <p>
                                                                                            <span><?php echo $direccion->ciudad->ciudad; ?></span>, <span><?php echo $direccion->provincia->provincia; ?></span>, <span><?php echo $direccion->cpostal; ?></span>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="pull-right">
                                                                                        <a  class="icon icon-checkmark-3 selectFacturacion"
                                                                                            href="#"
                                                                                            title="Seleccionar"
                                                                                            data-id="<?php echo $direccion->cliente_direccion_id ?>"
                                                                                            data-identificacion="<?php echo $direccion->identificacion ?>"
                                                                                            data-fullname="<?php echo $direccion->fullName ?>"
                                                                                            data-domicilio="<?php echo $direccion->fullDomicilio ?>"
                                                                                            data-provincia="<?php echo $direccion->provincia->provincia ?>"
                                                                                            data-ciudad="<?php echo $direccion->ciudad->ciudad ?>"
                                                                                            data-cpostal="<?php echo $direccion->cpostal ?>"
                                                                                        >
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </ul>
                                                                </div>
                                                                <a href="<?php echo Yii::app()->createAbsoluteUrl('/direcciones/create?returnTo=productos/checkout') ?>" class="btn btn-mega btn-mega-alt pull-right">CREAR DIRECCIONES</a>
                                                            </section>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <div class="col-md-12">
                                                            <div class="icon-circle-sm active"><span class="icon icon-notice"></span></div>
                                                            <h4>No tienes definidas direcciones</h4>
                                                            <p>Debes definir direcciones de facturación / entrega para poder procesar tu pedido.</p>
                                                            <a href="<?php echo Yii::app()->createAbsoluteUrl('/direcciones/create?returnTo=productos/checkout') ?>" class="btn btn-mega btn-mega-alt pull-right">CREAR DIRECCIONES</a>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree"> <span>3</span>DATOS DE RETIRO / ENTREGA </a> </h4>
                                        </div>
                                        <div id="collapseThree" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <?php
                                                    if (count($direcciones) != 0) {
                                                        ?>
                                                        <div class="col-sm-6">
                                                            <p>
                                                                <strong>
                                                                    <span class="entrega_identificacion"><?php echo $direcciones[0]->identificacion; ?></span>
                                                                </strong>
                                                            </p>
                                                            <p>
                                                                <span class="entrega_fullname"><?php echo $direcciones[0]->fullName; ?></span>
                                                            </p>
                                                            <p>
                                                                <span class="entrega_domicilio"><?php echo $direcciones[0]->fullDomicilio; ?></span>
                                                            </p>
                                                            <p>
                                                                <span class="entrega_ciudad"><?php echo $direcciones[0]->ciudad->ciudad; ?></span>
                                                            </p>
                                                            <p>
                                                                <span class="entrega_provincia"><?php echo $direcciones[0]->provincia->provincia; ?></span>, <span class="entrega_postal"><?php echo $direcciones[0]->cpostal; ?></span>
                                                            </p>
                                                            <input type="hidden" value="<?php echo $direcciones[0]->cliente_direccion_id ?>" class="entrega_id" name="entrega_id" />
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <h6>Seleccionar otra dirección</h6>

                                                            <section class="slider-products module">
                                                                <div class="products-widget jcarousel-skin-previews vertical">
                                                                    <ul class="slides">
                                                                        <?php
                                                                        foreach ($direcciones as $direccion) {
                                                                            ?>
                                                                            <li>
                                                                                <div class="product">
                                                                                    <div class="pull-left">
                                                                                        <p>
                                                                                            <strong>
                                                                                                <span><?php echo $direccion->identificacion; ?>, <span><?php echo $direccion->fullName; ?></span></span>
                                                                                            </strong>
                                                                                        </p>
                                                                                        <p>
                                                                                            <span><?php echo $direccion->fullDomicilio; ?></span>
                                                                                        </p>
                                                                                        <p>
                                                                                            <span><?php echo $direccion->ciudad->ciudad; ?></span>, <span><?php echo $direccion->provincia->provincia; ?></span>, <span><?php echo $direccion->cpostal; ?></span>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="pull-right">
                                                                                        <a  class="icon icon-checkmark-3 selectEntrega"
                                                                                            href="#"
                                                                                            title="Seleccionar"
                                                                                            data-id="<?php echo $direccion->cliente_direccion_id ?>"
                                                                                            data-identificacion="<?php echo $direccion->identificacion ?>"
                                                                                            data-fullname="<?php echo $direccion->fullName ?>"
                                                                                            data-domicilio="<?php echo $direccion->fullDomicilio ?>"
                                                                                            data-provincia="<?php echo $direccion->provincia->provincia ?>"
                                                                                            data-ciudad="<?php echo $direccion->ciudad->ciudad ?>"
                                                                                            data-cpostal="<?php echo $direccion->cpostal ?>"
                                                                                        >
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </li>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </ul>
                                                                </div>
                                                                <a href="<?php echo Yii::app()->createAbsoluteUrl('/direcciones/create?returnTo=productos/checkout') ?>" class="btn btn-mega btn-mega-alt pull-right">CREAR DIRECCIONES</a>
                                                            </section>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <div class="col-md-12">
                                                            <div class="icon-circle-sm active"><span class="icon icon-notice"></span></div>
                                                            <h4>No tienes definidas direcciones</h4>
                                                            <p>Debes definir direcciones de facturación / entrega para poder procesar tu pedido.</p>
                                                            <a href="<?php echo Yii::app()->createAbsoluteUrl('/direcciones/create?returnTo=productos/checkout') ?>" class="btn btn-mega btn-mega-alt pull-right">CREAR DIRECCIONES</a>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="divider divider-sm visible-sm  visible-xs"></div>
                                <div class="clearfix"><br></div>
                                <section class="pull-right">
                                    <input
                                        type="submit"
                                        class="btn btn-mega"
                                        value="REALIZAR PAGO"
                                        <?php if (count($direcciones) == 0) { ?>
                                            disabled="disabled"
                                            title="Debes definir direcciones de facturación / entrega para procesar tu pedido."
                                        <?php } ?>
                                    />
                                </section>
                            </form>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </section>
            <!-- //end Shopping cart -->
        </section>
        <section class="col-md-5 col-lg-5">
            <div class="shopping_cart">
                <div class="box">
                    <div class="shopping_cart_detail">
                        <h3>TU PEDIDO</h3>
                        <table>
                            <thead>
                            <tr class="hidden-xs">
                                <th></th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($items as $item) {
                                if ($item->producto) {
                                    //echo "<pre>"; var_dump($item->producto); die;
                                    ?>
                                    <tr class="cart-item-row">
                                        <td>
                                            <a href="#"
                                               class="remove-button visible-xs"
                                               data-categoria="<?php echo $item->producto->categoria->categoria_id ?>"
                                               data-producto="<?php echo $item->producto->producto_id ?>">
                                                <span class="icon-cancel-2 "></span>
                                            </a>
                                            <a href="#">
                                                <img class="preview"
                                                     src="<?php echo Commons::image('repository/' . $item->producto->foto, 52, 52) ?>">
                                            </a>
                                        </td>
                                        <td>
                                            <span class="td-name visible-xs">Producto</span><a
                                                href="#"><?php echo $item->producto->etiqueta ?></a>
                                        </td>
                                        <td><span class="td-name visible-xs">Cantidad</span>
                                            <div class="input-group quantity-control cart-quantity-control">
                                                <a href="#"><?php echo $item->cantidad ?></a>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="td-name visible-xs">Total</span>
                                            $<span class="cart-item-total"><?php echo Commons::formatPrice($item->producto->precio[ProductosPrecios::PRECIO_ONLINE_ID]['precio'] * $item->cantidad) ?></span>
                                        </td>
                                    </tr>
                                    <?php
                                    $total += $item->producto->precio[ProductosPrecios::PRECIO_ONLINE_ID]['precio'] * $item->cantidad;
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                        <div> <b class="title">Costo de envío:</b> Gratis </div>
                        <div>
                            <p><b class="title">Total:</b> <span class="price">$<span class="cartTotal"><?php echo Commons::formatPrice($total) ?></span></span></p>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </section>
    </div>
</section>
<!-- //end Two columns content -->