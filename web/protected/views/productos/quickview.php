<?php if ($fancy === true) { ?>
<div class="product-preview-popup">
    <div class="product-view product-view-compact"> <a href="javascript:jQuery.fancybox.close();" class="close-view"><span class="icon-cancel-3"></span></a>
<?php } ?>
        <div class="">
            <div class="col-sm-4 col-md-4 col-lg-4">
                <div class="large-image"> <img class = "cloudzoom" id="cloudzoom1" src = "<?= Commons::image('repository/' . $producto->primeraFotoGaleria(), 593, 722) ?>" data-cloudzoom = "zoomImage: '<?= Commons::image('repository/' . $producto->primeraFotoGaleria(), 600, 600) ?>', autoInside : 991" /> </div>
                <?php
                if (count($producto->multimedia)) {
                  //setup flexslider-thumb class when slider is needed ( > 3 items)
                  $class = '';
                  if ( count($producto->multimedia) > 3 )
                    $class = 'flexslider-thumb';
                    ?>
                    <div>
                        <div class="flexslider <?= $class ?>">
                            <ul class="previews-list slides">
                                <?php
                                foreach ($producto->multimedia as $m) {
                                    if ($m->tipo == ProductosMultimedia::TYPE_IMAGE) {
                                        ?>
                                        <li><img class='cloudzoom-gallery' alt="#"
                                                 src="<?= Commons::image('repository/' . $m->url, 76, 92) ?>"
                                                 data-cloudzoom="useZoom: '.cloudzoom', image: '<?= Commons::image('repository/' . $m->url, 593, 722) ?>', zoomImage: '<?= Commons::image('repository/' . $m->url, 600, 600) ?>'">
                                        </li>
                                        <?php
                                    } else {
                                        ?>
                                        <li>
                                            <a class="various fancybox.iframe"
                                               href="http://www.youtube.com/embed/<?= ProductosMultimedia::getYoutubeId($m->url) ?>?autoplay=1">
                                                <img alt="#"
                                                     class='fancybox-video'
                                                     src="<?= Yii::app()->theme->baseUrl ?>/images/video.png">
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
            <div class="info-cell col-sm-<?= $center_cols ?> col-md-<?= $center_cols ?> col-lg-<?= $center_cols ?>">
                <?php/*
                $this->widget('ext.SocialShareButton.SocialShareButton', array(
                    'style'    => 'horizontal',
                    'networks' => ['facebook','twitter','pinterest'],
                    'data_via' => '', //twitter username (for twitter only, if exists else leave empty)
                ));
                ?><br>
                */?>
                <h2><?= $producto->etiqueta ?></h2>
                <div class="clearfix"></div>
                <?php
                if ($producto->promocion) {
                    $this->widget('CountdownPromocion',
                        [
                          'extraClass' => 'countdown_quickview',
                          'promocion'  => $producto->promocion
                        ]
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
                            <li><span class="label">-<?= $producto->promocion->porcentaje_promocion ?>%</span></li>
                            <?php
                        } else {
                            ?>
                            <li><span class="label">-<?= $producto->oferta[0]->descuento ?>%</span></li>
                            <?php
                        }
                        ?>
                    </ul>
                <?php } ?>
                <div class="line-divider"></div>
                <div class="price-part col-md-6">
                    <?php
                    if (ProductosPrecios::$PRECIO_LISTA_ID != Precios::NINGUNO)
                      echo '<span class="price lista">Precio de lista: $'.Commons::formatPrice($producto->precio[ProductosPrecios::$PRECIO_LISTA_ID]['precio']).'</span>';

                    if ($producto->isOferta === true) {
                       //if ($producto['promocion']) {
                      //      if ($producto->precio[ProductosPrecios::$PRECIO_AUX_ID]['precio'] > 0)
                      //        echo '<span class="price old">$'.Commons::formatPrice($producto->precio[ProductosPrecios::$PRECIO_AUX_ID]['precio']).'</span>';
                      //  } else {
                      if ($producto->precio[ProductosPrecios::PRECIO_AUX_ID]['precio'] > 0)
                        echo '<span class="price old">$'.Commons::formatPrice($producto->precio[ProductosPrecios::PRECIO_AUX_ID]['precio']).'</span>';
                      //  }
                    }

                    if (ProductosPrecios::$PRECIO_ONLINE_ID != Precios::NINGUNO)
                      echo '<span class="price new_text">Precio Contado/Efectivo: </span>';
                      echo '<span class="price new">$'.Commons::formatPrice($producto->precio[ProductosPrecios::$PRECIO_ONLINE_ID]['precio']).'</span>';

                    if (isset($producto->cuotas_sobre_precio) && $producto->cuotas_sobre_precio != 1) { ?>
                        <span class="price cuotas"><?= $producto->cuotas_sobre_precio ?> x $<?= Commons::formatPrice($producto->precio[ProductosPrecios::PRECIO_AUX_CUOTAS_SOBRE_PRECIO]->precio, 2) ?></span>
                         <span class="price cuotas_final">(Precio financiado $<?= Commons::formatPrice($producto->precio[ProductosPrecios::$PRECIO_TARJETA_ID]['precio'], 2) ?>)</span>

                    <?php } ?>
                    <a href="<?= Yii::app()->createAbsoluteUrl('site/contactoProducto/'.$categoria_id.'/'.$producto_id) ?>" class="btn btn-mega btn-md contact-from-product">Consultar Producto</a>
                </div>
                <div class="quantity-add-part col-md-6">
                    <div class="option"> <b>Cantidad:</b>
                        <div class="input-group quantity-control" unselectable="on" style="-moz-user-select: none;">
                            <span class="input-group-addon">−</span>
                            <input type="text" value="1" class="form-control detail-input-cantidad">
                            <span class="input-group-addon">+</span> </div>
                    </div>
                    <div class="clearfix visible-xs"></div>
                    <input type="hidden" value="<?= $categoria_id ?>" id="detail_categoria_id" />
                    <input type="hidden" value="<?= $producto_id ?>" id="detail_producto_id" />
                    <?= CHtml::hiddenField('productLevel', $producto->stock, array('data-categoria' => $categoria_id, 'class' => 'detail-input-cantidad', 'data-producto' => $producto_id)); ?>
                    <button <?php if ($producto->stock <=0) echo 'disabled="disabled"'; ?> class="btn btn-mega btn-md detail-add-to-cart" type="submit" data-categoria="<?= $categoria_id ?>" data-producto="<?= $producto_id ?>">Comprar</button>
                </div>
                <div class="clearfix"></div>
                <div class="line-divider"></div>
                <?php if ($fancy !== true) { ?>
                  <h6>Medios de pago - Consultá promociones</h6>
                <?php $this->widget('HtmlList',[
                    'elements' => PedidoOnline::getFormasPagoData(['img','enlacePromo']), // se hace una lista de las formas de pago con solo sus img
                    'ul' => ['class' => 'payment-list'],
                  ]);
                  ?>
                <div class="clearfix"><br /></div>
                <div class="line-divider"></div>
                <?php } ?>
                <?php if (ProductosPrecios::$PRECIO_CONTADO_ID != Precios::NINGUNO && isset($producto->precio[ProductosPrecios::$PRECIO_CONTADO_ID]['precio'])) { ?>
                    <p class="legend_price_contado">
                        <?= CHtml::image(Yii::app()->theme->baseUrl . '/images/compralo.png'); ?>
                        <span class="quickDetailContado">$<strong><?= Commons::formatPrice($producto->precio[ProductosPrecios::$PRECIO_CONTADO_ID]['precio']) ?></strong></span>
                    </p>
                    <div class="line-divider"></div>
                    <div class="clearfix"><br /></div>
                <?php } ?>
                <?php/* if (ProductosPrecios::$PRECIO_TRANSFERENCIA_ID != Precios::NINGUNO && isset($producto->precio[ProductosPrecios::$PRECIO_TRANSFERENCIA_ID]['precio'])) { ?>
                    <h6>Precio transferencia bancaria directa</h6>
                    <span>$<strong><?= Commons::formatPrice($producto->precio[ProductosPrecios::$PRECIO_TRANSFERENCIA_ID]['precio']) ?></strong></span>
                    <div class="line-divider"></div>
                    <div class="clearfix"><br /></div>
                <?php } */?>
                <?php if ($hide_ver_mas !== true) { ?>
                    <a
                        href="<?= Yii::app()->createAbsoluteUrl('/productos/detail/'.$categoria_id.'/'.$producto_id) ?>"
                        class="btn btn-mega btn-mega-alt btn-md">Ver más</a>
                    <div class="clearfix"><br /></div>
                <?php }
                  //otro codigo que espero temporal, armamos el html del body de la tabla
                  $HTMLCaracteristicas = '';
                  foreach ($producto->campos as $campo) {
                      if ($producto['field_' . $campo['slug']] == '') {
                          continue;
                      }
                      $HTMLCaracteristicas .= '
                      <tr><td><strong>'.$campo['nombre'].'</strong></td>
                          <td>'.$producto['field_' . $campo['slug']].'</td></tr>';
                  }

                  $this->widget('BootstrapPanelGroup',[
                    'gral'   => ['class' => 'accordion-simple', 'id' => 'product-accordion', 'panels' => ['class'=>'']],
                    'panels' => [
                      [
                        'title'   => ['text' => '<span class="arrow-down icon-arrow-down-4"></span> <span class="arrow-up icon-arrow-up-4"></span> Descripción',],
                        'content' => '<p>'.$producto->descripcion.'</p>',
                        'id'      => 'product-description',
                      ],
                      [
                        'title'   => ['text' => '<span class="arrow-down icon-arrow-down-4"></span> <span class="arrow-up icon-arrow-up-4"></span> Características ',],
                        'content' => $this->widget('HTMLTable',[
                          'columns' => [],
                          'table'   => ['class' => 'table table-striped'],
                          'body'    => $HTMLCaracteristicas,
                        ],true),
                        'id'      => 'product-ficha',
                      ],
                    ],
                  ]);
                ?>

                <div class="clearfix"></div>
                <div class="line-divider"></div>
                <?php/*
                $this->widget('ext.SocialShareButton.SocialShareButton', array(
                    'style'=>'horizontal',
                    'networks' => array('facebook','twitter','pinterest'),
                    'data_via'=>'', //twitter username (for twitter only, if exists else leave empty)
                ));
                ?>
                <div class="clearfix"><br /><br /><br /></div>
                */?>
            </div>
        </div>
<?php if ($fancy === true) { ?>
    </div>
</div>
<?php } ?>