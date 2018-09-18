<?php
if (count($destacados)) {
    ?>
    <section class="tMar2">
        <h3>Más vendidos</h3>
        <!-- Products list -->
        <div class="">
            <div class="product-carousel">
                <?php
                foreach ($destacados as $novedad) {
                    //echo "<pre>"; var_dump($novedad->producto->oferta[0]->descuento); die;
                    ?>
                    <div class="item">
                        <div class="product-preview">
                            <div class="preview animate scale">
                                <a href="<?php echo Yii::app()->createAbsoluteUrl('/productos/detail/' . $novedad->categoria_id . '/' . $novedad->producto_id) ?>"
                                   class="preview-image">
                                    <img
                                        class="img-responsive animate scale"
                                        src="<?php echo Commons::image('repository/'.$novedad->producto->foto, 270, 328); ?>"
                                        width="270" height="328" alt="">
                                </a>
                                <?php if ($countdown === false) {
                                    $this->widget('CountdownPromocion',
                                        array(
                                            'extraClass' => '',
                                            'promocion' => $novedad->promocion
                                        )
                                    );
                                } ?>
                                <?php if ($nuevo === true) { ?>
                                    <ul class="product-controls-list">
                                        <li><span class="label label-new">NUEVO</span></li>
                                    </ul>
                                <?php } ?>
                                <?php if ($novedad->producto->isOferta === true) { ?>
                                    <ul class="product-controls-list right">
                                        <li><span class="label label-sale">AHORRO</span></li>
                                        <li><span class="label">$<?php echo Commons::formatPrice($novedad->producto->oferta[0]->producto->precio[ProductosPrecios::PRECIO_AHORRO_ID]) ?></span></li>
                                    </ul>
                                <?php } ?>
                                <ul class="product-controls-list right hide-right">
                                    <li class="top-out"></li>
                                    <?php echo CHtml::hiddenField('productLevel', $novedad->producto->stock, array('data-categoria' => $novedad->producto->categoria->categoria_id, 'class' => 'detail-input-cantidad', 'data-producto' => $novedad->producto->producto_id)); ?>
                                    <li class="hidden"><a href="#" class="cart" data-categoria="<?php echo $novedad->producto->categoria->categoria_id ?>" data-producto="<?php echo $novedad->producto->producto_id ?>"><span class="icon-basket"></span></a></li>
                                </ul>
                                <a
                                    href="<?php echo Yii::app()->createAbsoluteUrl('/productos/quickview/' . $novedad->categoria_id . '/' . $novedad->producto_id) ?>"
                                    class="quick-view fancybox fancybox.ajax hidden-xs">
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
                                <a href="<?php echo Yii::app()->createAbsoluteUrl('/productos/detail/' . $novedad->categoria_id . '/' . $novedad->producto_id) ?>">
                                    <?php echo $novedad->producto->etiqueta ?>
                                </a>
                            </h3>
                            <span class="price lista">Precio de lista: $<?php echo Commons::formatPrice($novedad->producto->precio[12]['precio']) ?></span>
                            <span class="price new">$<?php echo Commons::formatPrice($novedad->producto->precio[15]['precio']) ?></span>
                            <?php if (isset($novedad->producto->cuotas_sobre_precio)) { ?>
                                <span class="price cuotas"><?php echo $novedad->producto->cuotas_sobre_precio ?> x $<?php echo Commons::formatPrice($novedad->producto->precio[ProductosPrecios::PRECIO_AUX_CUOTAS_SOBRE_PRECIO]->precio, 2) ?></span>
                            <?php } ?>
                            <a href="#" class="cart btn btn-mega btn-md detail-add-to-cart" data-categoria="<?php echo $novedad->producto->categoria->categoria_id ?>" data-producto="<?php echo $novedad->producto->producto_id ?>">Comprar</a>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <!-- //end Products list -->

            <!-- Product view compact -->
            <div class="product-view-ajax">
                <div class="ajax-loader progress progress-striped active">
                    <div class="progress-bar progress-bar-danger" role="progressbar"></div>
                </div>
                <div class="layar"></div>
                <div class="product-view-container"></div>
            </div>
            <!-- //end Product view compact -->
        </div>

    </section>
    <?php
}
?>