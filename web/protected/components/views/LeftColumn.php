<!-- Left column -->
<aside class="col-sm-4 col-md-3 col-lg-3 content-aside">

    <?php $this->widget('CategoriesTree') ?>

    <!-- BEST SELLERS -->
    <?php
    if (count($destacados)) {
        ?>
        <div class="section-divider"></div>

        <h3>MAS VENDIDOS</h3>
        <div class="products-destacados products-widget jcarousel-skin-previews jcarousel-skin-previews-4 vertical">
            <ul class="slides">
                <?php
                foreach ($destacados as $destacado) {
                    $link = Yii::app()->createAbsoluteUrl('/productos/detail/' . $destacado->producto->categoria->categoria_id . '/' . $destacado->producto->producto_id);
                    ?>
                    <li>
                        <div class="product">
                            <a href="<?php echo $link ?>" class="preview-image">
                                <img class="img-responsive animate scale"
                                     src="<?php echo Commons::image('repository/' . $destacado->producto->foto, 126, 153) ?>"
                                     alt="">
                            </a>
                            <p class="name">
                                <a href="<?php echo $link ?>"
                                   class="preview-image"><?php echo $destacado->producto->etiqueta ?></a>
                            </p>
                            <?php if ($destacado->producto->isOferta) { ?>
                                <span
                                    class="price old">$<?php echo Commons::formatPrice($destacado->producto->oferta[0]->producto->precio[99]) ?></span>
                            <?php } ?>
                            <span
                                class="price new">$<?php echo Commons::formatPrice($destacado->producto->precio[15]['precio']) ?></span>
                            <?php if (isset($destacado->producto->cuotas_sobre_precio)) { ?>
                                <span class="price cuotas"><?php echo $destacado->producto->cuotas_sobre_precio ?>
                                    x $<?php echo Commons::formatPrice($destacado->producto->precio[ProductosPrecios::PRECIO_AUX_CUOTAS_SOBRE_PRECIO]->precio, 2) ?></span>
                            <?php } ?>
                        </div>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <?php
    }
    ?>
    <!-- //end Best Sellers -->

    <!-- Blog Widget Small -->
    <?php
    if (count($publicaciones)) {
        ?>
        <div class="section-divider"></div>
        <section class="blog-widget-small">
            <h3>PUBLICACIONES</h3>
            <div class="posts flexslider">
                <ul class="slides">
                    <?php
                    foreach ($publicaciones as $publicacion) {
                        $link = $publicacion->ampliar == 1 ? Yii::app()->createAbsoluteUrl('/publicaciones/' . $publicacion->publicacion_id) : false;
                        $image = $publicacion->imagen_destacada != '' && file_exists(Yii::app()->basePath . '/../../repository/' . $publicacion->imagen_destacada)
                            ? Commons::image('repository/' . $publicacion->imagen_destacada, 270, 159)
                            : Yii::app()->theme->baseUrl . '/images/temp/block-image-04-270x159.jpg';
                        ?>
                        <li>
                            <div class="image-cell">
                                <a <?php if ($link) { ?> href="<?php echo $link; ?>" <?php } ?>>
                                    <img src="<?php echo $image; ?>" class="img-responsive animate scale" alt="">
                                </a>
                            </div>
                            <div class="offset-image">
                                <h4>
                                    <a <?php if ($link) { ?> href="<?php echo $link; ?>" <?php } ?>><?php echo $publicacion->titulo; ?></a>
                                </h4>
                                <?php echo $publicacion->bajada; ?>
                            </div>
                            <?php if ($link) { ?>
                                <div class="pull-right">
                                    <a href="<?php echo $link; ?>" class="btn btn-mega btn-mega-alt">leer más</a>
                                </div>
                            <?php } ?>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </section>
        <?php
    }
    ?>
    <!-- //end Blog Widjet Small -->
</aside>
<!-- //end Left column -->