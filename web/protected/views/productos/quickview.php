<?php if ($fancy === true) { ?>
<div class="product-preview-popup">
    <div class="product-view product-view-compact"> <a href="javascript:jQuery.fancybox.close();" class="close-view"><span class="icon-cancel-3"></span></a>
<?php } ?>
        <div class="">
            <div class="col-sm-4 col-md-4 col-lg-4">
                <div class="large-image"> <img class = "cloudzoom" id="cloudzoom1" src = "<?php echo Commons::image('repository/' . $producto->foto, 593, 722) ?>" data-cloudzoom = "zoomImage: '<?php echo Commons::image('repository/' . $producto->foto, 600, 600) ?>', autoInside : 991" /> </div>
                <?php
                if (count($producto->multimedia)) {
                    ?>
                    <div>
                        <div class="flexslider flexslider-thumb">
                            <ul class="previews-list slides">
                                <li><img class='cloudzoom-gallery' alt="#"
                                         src="<?php echo Commons::image('repository/' . $producto->foto, 76, 92) ?>"
                                         data-cloudzoom="useZoom: '.cloudzoom', image: '<?php echo Commons::image('repository/' . $producto->foto, 593, 722) ?>', zoomImage: '<?php echo Commons::image('repository/' . $producto->foto, 600, 600) ?>', autoInside : 991">
                                </li>
                                <?php
                                foreach ($producto->multimedia as $m) {
                                    if ($m->tipo == ProductosMultimedia::TYPE_IMAGE) {
                                        ?>
                                        <li><img class='cloudzoom-gallery' alt="#"
                                                 src="<?php echo Commons::image('repository/' . $m->url, 76, 92) ?>"
                                                 data-cloudzoom="useZoom: '.cloudzoom', image: '<?php echo Commons::image('repository/' . $m->url, 593, 722) ?>', zoomImage: '<?php echo Commons::image('repository/' . $m->url, 600, 600) ?>'">
                                        </li>
                                        <?php
                                    } else {
                                        ?>
                                        <li>
                                            <a class="various fancybox.iframe"
                                               href="http://www.youtube.com/embed/<?php echo ProductosMultimedia::getYoutubeId($m->url) ?>?autoplay=1">
                                                <img alt="#"
                                                     class='fancybox-video'
                                                     src="<?php echo Yii::app()->theme->baseUrl ?>/images/video.png">
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="info-cell col-sm-<?php echo $center_cols ?> col-md-<?php echo $center_cols ?> col-lg-<?php echo $center_cols ?>">
                <?php
                $this->widget('ext.SocialShareButton.SocialShareButton', array(
                    'style'=>'horizontal',
                    'networks' => array('facebook','twitter','pinterest'),
                    'data_via'=>'', //twitter username (for twitter only, if exists else leave empty)
                ));
                ?><br>
                <h2><?php echo $producto->etiqueta ?></h2>
                <div class="clearfix"></div>
                <?php
                if ($producto->promocion) {
                    $this->widget('CountdownPromocion',
                        array(
                            'extraClass' => 'countdown_quickview',
                            'promocion' => $producto->promocion
                        )
                    );
                }
                ?>
                <div class="clearfix"></div>
                <?php if ($producto->isOferta === true) { ?>
                    <ul class="product-controls-list right">
                        <li><span class="label label-sale">OFERTA</span></li>
                        <?php
                        if ($producto['promocion']) {
                            ?>
                            <li><span class="label">-<?php echo $producto->promocion->porcentaje_promocion ?>%</span></li>
                            <?php
                        } else {
                            ?>
                            <li><span class="label">-<?php echo $producto->oferta[0]->descuento ?>%</span></li>
                            <?php
                        }
                        ?>
                    </ul>
                <?php } ?>
                <div class="line-divider"></div>
                <div class="price-part col-md-6">
                    <span class="price lista">Precio de lista: $<?php echo Commons::formatPrice($producto->precio[ProductosPrecios::PRECIO_TARJETA_ID]['precio']) ?></span>
                    <?php
                    if ($producto->isOferta === true) {
                        ?>
                        <?php
                        if ($producto['promocion']) {
                            ?>
                            <span class="price old">$<?php echo Commons::formatPrice($producto->precio[ProductosPrecios::PRECIO_AUX_ID]['precio']) ?></span>
                            <?php
                        } else {
                            ?>
                            <span class="price old">$<?php echo Commons::formatPrice($producto->oferta[0]['producto']->precio[ProductosPrecios::PRECIO_AUX_ID]) ?></span>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
                    <span class="price new">$<?php echo Commons::formatPrice($producto->precio[ProductosPrecios::PRECIO_ONLINE_ID]['precio']) ?></span>
                    <?php if (isset($producto->cuotas_sobre_precio)) { ?>
                        <span class="price cuotas"><?php echo $producto->cuotas_sobre_precio ?> x $<?php echo Commons::formatPrice($producto->precio[ProductosPrecios::PRECIO_AUX_CUOTAS_SOBRE_PRECIO]->precio, 2) ?></span>
                    <?php } ?>
                </div>
                <div class="quantity-add-part col-md-6">
                    <div class="option"> <b>Cantidad:</b>
                        <div class="input-group quantity-control" unselectable="on" style="-moz-user-select: none;">
                            <span class="input-group-addon">−</span>
                            <input type="text" value="1" class="form-control detail-input-cantidad">
                            <span class="input-group-addon">+</span> </div>
                    </div>
                    <div class="clearfix visible-xs"></div>
                    <input type="hidden" value="<?php echo $categoria_id ?>" id="detail_categoria_id" />
                    <input type="hidden" value="<?php echo $producto_id ?>" id="detail_producto_id" />
                    <?php echo CHtml::hiddenField('productLevel', $producto->stock, array('data-categoria' => $categoria_id, 'class' => 'detail-input-cantidad', 'data-producto' => $producto_id)); ?>
                    <button class="btn btn-mega btn-md detail-add-to-cart" type="submit" data-categoria="<?php echo $categoria_id ?>" data-producto="<?php echo $producto_id ?>">Comprar</button>
                </div>

                <div class="clearfix"><br /></div>
                <div class="line-divider"></div>
                <?php if ($fancy !== true) { ?>
                    <h6>Medios de pago - Consultá promociones</h6>
                    <ul class="payment-list">
                        <li><a href="http://www.todopago.com.ar/promociones-vigentes" target="_blank"><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/icon-payment-01.png" width="54" height="40" alt="" style="alignment-adjust: central;"></a></li>
                        <li><a href="https://www.mercadopago.com.ar/promociones" data-fancybox-type="iframe" class="fancybox fancyMp"><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/icon-payment-02.png" width="54" height="40" alt=""></a></li>
                    </ul>
                    <div class="clearfix"><br /></div>
                    <div class="line-divider"></div>
                <?php } ?>
                <?php if (isset($producto->precio[ProductosPrecios::PRECIO_CONTADO_ID]['precio'])) { ?>
                    <p class="legend_price_contado">
                        <?php
                        echo CHtml::image(Yii::app()->theme->baseUrl . '/images/compralo.png');
                        ?>
                        <span class="quickDetailContado">$<strong><?php echo Commons::formatPrice($producto->precio[ProductosPrecios::PRECIO_CONTADO_ID]['precio']) ?></strong></span>
                    </p>
                    <div class="line-divider"></div>
                    <div class="clearfix"><br /></div>
                <?php } ?>
                <?php if ($hide_ver_mas !== true) { ?>
                    <a
                        href="<?php echo Yii::app()->createAbsoluteUrl('/productos/detail/'.$categoria_id.'/'.$producto_id) ?>"
                        class="btn btn-mega btn-mega-alt btn-md">Ver más</a>
                    <div class="clearfix"><br /></div>
                <?php } ?>
                <div class="panel-group accordion-simple" id="product-accordion">
                    <div class="panel">
                        <div class="panel-heading"> <a data-toggle="collapse" data-parent="#product-accordion" href="#product-description" class="collapsed"> <span class="arrow-down icon-arrow-down-4"></span> <span class="arrow-up icon-arrow-up-4"></span> Descripción </a> </div>
                        <div id="product-description" class="panel-collapse collapse">
                            <div class="panel-body">
                                <p><?php echo $producto->descripcion ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-heading"> <a data-toggle="collapse" data-parent="#product-accordion" href="#product-ficha"> <span class="arrow-down icon-arrow-down-4"></span> <span class="arrow-up icon-arrow-up-4"></span> Características </a> </div>
                        <div id="product-ficha" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <table class="table table-striped">
                                    <?php
                                    foreach ($producto->campos as $campo) {
                                        if ($producto['field_' . $campo['slug']] == '') {
                                            continue;
                                        }
                                        ?>
                                        <tr>
                                            <td><strong><?php echo $campo['nombre'] ?></strong></td>
                                            <td><?php echo $producto['field_' . $campo['slug']] ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="line-divider"></div>
                <?php
                $this->widget('ext.SocialShareButton.SocialShareButton', array(
                    'style'=>'horizontal',
                    'networks' => array('facebook','twitter','pinterest'),
                    'data_via'=>'', //twitter username (for twitter only, if exists else leave empty)
                ));
                ?>
                <div class="clearfix"><br /><br /><br /></div>
            </div>
        </div>
<?php if ($fancy === true) { ?>
    </div>
</div>
<?php } ?>