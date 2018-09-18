<?php
/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 05/07/16
 * Time: 23:51
 */
$this->pageTitle = Yii::app()->name . ' :: ' . 'Carrito de compras';
?>
<!-- Breadcrumbs -->
<section class="container">
    <nav class="breadcrumbs">
        <a href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">Home</a>
        <span class="divider">›</span> Carrito de compras
    </nav>
</section>
<!-- //end Breadcrumbs -->

<!-- Two column content -->
<section class="container">
    <div class="row">
        <section class="col-md-12 col-lg-12">
            <!-- Shopping cart -->
            <section class="content-box">
                <div class="shopping_cart">
                    <div class="box">
                        <div class="shopping_cart_detail">
                            <h3>Carrito de compras</h3>
                            <table>
                                <thead>
                                <tr class="hidden-xs">
                                    <th></th>
                                    <th></th>
                                    <th>Producto</th>
                                    <th>Unitario</th>
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
                                                   class="remove-button hidden-xs"
                                                   data-categoria="<?php echo $item->producto->categoria->categoria_id ?>"
                                                   data-producto="<?php echo $item->producto->producto_id ?>">
                                                    <span class="icon-cancel-2 "></span>
                                                </a>
                                            </td>
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
                                                    href="#"><?php echo $item->producto->etiqueta ?></a></td>
                                            <td><span
                                                    class="td-name visible-xs">Unitario</span>$<span class="cart-item-unitario"><?php echo Commons::formatPrice($item->producto->precio[ProductosPrecios::PRECIO_ONLINE_ID]['precio']) ?></span>
                                            </td>
                                            <td><span class="td-name visible-xs">Cantidad</span>
                                                <div class="input-group quantity-control cart-quantity-control">
                                                <span class="input-group-addon cart-remove-one"
                                                      data-categoria="<?php echo $item->producto->categoria->categoria_id ?>"
                                                      data-producto="<?php echo $item->producto->producto_id ?>">&minus;</span>
                                                    <input type="text" class="form-control cart-item-cantidad"
                                                           value="<?php echo $item->cantidad ?>"
                                                           data-cantidad="<?php echo $item->cantidad ?>"
                                                           data-categoria="<?php echo $item->producto->categoria->categoria_id ?>"
                                                           data-producto="<?php echo $item->producto->producto_id ?>" />
                                                    <input type="hidden" class="cart-item-unitario-h" value="<?php echo $item->producto->precio[ProductosPrecios::PRECIO_ONLINE_ID]['precio'] ?>" />
                                                <span
                                                    class="input-group-addon cart-add-one"
                                                    data-categoria="<?php echo $item->producto->categoria->categoria_id ?>"
                                                    data-producto="<?php echo $item->producto->producto_id ?>">+</span></div>
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
                            <div class="pull-left"> <b class="title">Costo de envío:</b> Gratis </div>
                            <div class="pull-right">
                                <p><b class="title">Total:</b> <span class="price">$<span class="cartTotal"><?php echo Commons::formatPrice($total) ?></span></span></p>
                                <a href="<?php echo Yii::app()->createAbsoluteUrl('/productos/checkout') ?>" class="btn btn-mega">FINALIZAR COMPRA</a>
                            </div>
                        </div>
                        <div class="shopping_cart_empty hidden">
                            <div class="col-sm-9 col-md-9 col-lg-9">
                                <div class="icon-circle-sm active"><span class="icon icon-cart"></span></div>
                                <h5>No tienes items en el carrito</h5>
                                <p>Empieza a disfrutar de nuestras promociones ahora mismo.</p>
                            </div>
                            <div class="col-lg-3">
                                <a class="btn btn-mega btn-lg" href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">IR A HOME</a>
                            </div>
                            <h4 class="text-center"></h4>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </section>
            <!-- //end Shopping cart -->
        </section>
    </div>
</section>
<!-- //end Two columns content -->