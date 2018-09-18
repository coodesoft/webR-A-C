<?php
if (count($ofertas) || count($promociones)) {
    ?>
    <section>
        <h3>Ofertas</h3>
        <!-- Products list -->
        <div class="">
            <div class="product-carousel">
                <?php
                foreach ($promociones as $oferta) {
                    //Commons::dump($oferta->producto['precio']);
                    $linkDetail = Yii::app()->createAbsoluteUrl('/productos/detail/' . $oferta->categoria_id . '/' . $oferta->producto_id);
                    $linkQuickView = Yii::app()->createAbsoluteUrl('/productos/quickview/' . $oferta->categoria_id . '/' . $oferta->producto_id);
                    ?>
                    <div class="item">
                        <div class="product-preview">
                            <div class="preview animate scale">
                                <a href="<?php echo $linkDetail ?>" class="preview-image">
                                    <img
                                        class="img-responsive animate scale"
                                        src="<?php echo Commons::image('repository/'.$oferta->producto->foto, 270, 328); ?>"
                                        width="270" height="328" alt="">
                                </a>
                                <?php
                                $this->widget('CountdownPromocion',
                                    array(
                                        'extraClass' => '',
                                        'promocion' => $oferta->producto->promocion
                                    )
                                );
                                ?>
                                <?php if ($oferta->producto->novedad === true) { ?>
                                    <ul class="product-controls-list">
                                        <li><span class="label label-new">NUEVO</span></li>
                                    </ul>
                                <?php } ?>
                                <ul class="product-controls-list right">
                                    <li><span class="label label-sale">AHORRO</span></li>
                                    <li><span class="label">$<?php echo Commons::formatPrice($oferta->producto['precio'][ProductosPrecios::PRECIO_AHORRO_ID]['precio']) ?></span></li>
                                </ul>
                                <ul class="product-controls-list right hide-right">
                                    <li class="top-out"></li>
                                    <?php echo CHtml::hiddenField('productLevel', $oferta->producto->stock, array('data-categoria' => $oferta->producto->categoria->categoria_id, 'class' => 'detail-input-cantidad', 'data-producto' => $oferta->producto->producto_id)); ?>
                                    <li class="hidden"><a href="#" class="cart" data-categoria="<?php echo $oferta->producto->categoria->categoria_id ?>" data-producto="<?php echo $oferta->producto->producto_id ?>"><span class="icon-basket"></span></a></li>
                                </ul>
                                <a href="<?php echo $linkQuickView ?>" class="quick-view fancybox fancybox.ajax hidden-xs">
                                    <span class="rating hidden">
                                        <i class="icon-star-3"></i>
                                        <i class="icon-star-3"></i>
                                        <i class="icon-star-3"></i>
                                        <i class="icon-star-3"></i>
                                        <i class="icon-star-empty"></i>
                                    </span>
                                    <span class="icon-zoom-in-2"></span> Vista rápida
                                </a>

                                <div class="quick-view visible-xs hidden">
                                <span class="rating">
                                    <i class="icon-star-3"></i>
                                    <i class="icon-star-3"></i>
                                    <i class="icon-star-3"></i>
                                    <i class="icon-star-3"></i>
                                    <i class="icon-star-empty"></i>
                                </span>
                                </div>
                            </div>
                            <h3 class="title">
                                <a href="<?php echo $linkDetail ?>"><?php echo $oferta->producto->etiqueta ?></a>
                            </h3>
                            <span class="price lista">Precio de lista: $<?php echo Commons::formatPrice($oferta->producto->precio[ProductosPrecios::PRECIO_TARJETA_ID]['precio']) ?></span>
                            <span class="price old">$<?php echo Commons::formatPrice($oferta->producto->precio[ProductosPrecios::PRECIO_AUX_ID]['precio']) ?></span>
                            <span class="price new">$<?php echo Commons::formatPrice($oferta->producto->precio[ProductosPrecios::PRECIO_ONLINE_ID]['precio']) ?></span>
                            <?php if (isset($oferta->producto->cuotas_sobre_precio)) { ?>
                            <span class="price cuotas"><?php echo $oferta->producto->cuotas_sobre_precio ?> x $<?php echo Commons::formatPrice($oferta->producto->precio[ProductosPrecios::PRECIO_AUX_CUOTAS_SOBRE_PRECIO]->precio, 2) ?></span>
                            <?php } ?>
                            <a href="#" class="cart btn btn-mega btn-md detail-add-to-cart" data-categoria="<?php echo $novedad->producto->categoria->categoria_id ?>" data-producto="<?php echo $novedad->producto->producto_id ?>">Comprar</a>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <?php
                foreach ($ofertas as $oferta) {
                    $linkDetail = Yii::app()->createAbsoluteUrl('/productos/detail/' . $oferta->categoria_id . '/' . $oferta->producto_id);
                    $linkQuickView = Yii::app()->createAbsoluteUrl('/productos/quickview/' . $oferta->categoria_id . '/' . $oferta->producto_id);
                    ?>
                    <div class="item">
                        <div class="product-preview">
                            <div class="preview animate scale">
                                <a href="<?php echo $linkDetail ?>" class="preview-image">
                                    <img
                                        class="img-responsive animate scale"
                                        src="<?php echo Commons::image('repository/'.$oferta->producto->foto, 270, 328); ?>"
                                        width="270" height="328" alt="">
                                </a>
                                <?php if ($oferta->producto->novedad === true) { ?>
                                    <ul class="product-controls-list">
                                        <li><span class="label label-new">NUEVO</span></li>
                                    </ul>
                                <?php } ?>
                                <ul class="product-controls-list right">
                                    <li><span class="label label-sale">AHORRO</span></li>
                                    <li><span class="label">$<?php echo Commons::formatPrice($oferta->producto->precio[ProductosPrecios::PRECIO_AHORRO_ID]) ?></span></li>
                                </ul>
                                <ul class="product-controls-list right hide-right">
                                    <li class="top-out"></li>
                                    <?php echo CHtml::hiddenField('productLevel', $oferta->producto->stock, array('data-categoria' => $oferta->producto->categoria->categoria_id, 'class' => 'detail-input-cantidad', 'data-producto' => $oferta->producto->producto_id)); ?>
                                    <li><a href="#" class="cart" data-categoria="<?php echo $oferta->producto->categoria->categoria_id ?>" data-producto="<?php echo $oferta->producto->producto_id ?>"><span class="icon-basket"></span></a></li>
                                </ul>
                                <a href="<?php echo $linkQuickView ?>" class="quick-view fancybox fancybox.ajax hidden-xs">
                                    <span class="rating hidden">
                                        <i class="icon-star-3"></i>
                                        <i class="icon-star-3"></i>
                                        <i class="icon-star-3"></i>
                                        <i class="icon-star-3"></i>
                                        <i class="icon-star-empty"></i>
                                    </span>
                                    <span class="icon-zoom-in-2"></span> Vista rápida
                                </a>

                                <div class="quick-view visible-xs hidden">
                                <span class="rating">
                                    <i class="icon-star-3"></i>
                                    <i class="icon-star-3"></i>
                                    <i class="icon-star-3"></i>
                                    <i class="icon-star-3"></i>
                                    <i class="icon-star-empty"></i>
                                </span>
                                </div>
                            </div>
                            <h3 class="title">
                                <a href="<?php echo $linkDetail ?>"><?php echo $oferta->producto->etiqueta ?></a>
                            </h3>
                            <span class="price lista">Precio de lista: $<?php echo Commons::formatPrice($oferta->producto->precio[ProductosPrecios::PRECIO_TARJETA_ID]) ?></span>
                            <span class="price old">$<?php echo Commons::formatPrice($oferta->producto->precio[ProductosPrecios::PRECIO_AUX_ID]) ?></span>
                            <span class="price new">$<?php echo Commons::formatPrice($oferta->producto->precio[ProductosPrecios::PRECIO_ONLINE_ID]) ?></span>
                            <?php if (isset($oferta->producto->cuotas_sobre_precio)) { ?>
                                <span class="price cuotas"><?php echo $oferta->producto->cuotas_sobre_precio ?> x $<?php echo Commons::formatPrice($oferta->producto->precio[ProductosPrecios::PRECIO_AUX_CUOTAS_SOBRE_PRECIO]->precio, 2) ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <!-- product view ajax container -->
            <div class="product-view-ajax-container"></div>
            <!-- //end product view ajax container -->
        </div>
        <!-- //end Products list -->

    </section>
    <?php
}
?>