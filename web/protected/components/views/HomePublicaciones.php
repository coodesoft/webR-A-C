<?php
if (count($publicaciones)) {
    ?>
    <section>
        <h3>Últimas noticias</h3>
        <!-- Products list -->
        <div class="">
            <div class="product-carousel noticias-carousel">
                <?php
                foreach ($publicaciones as $publicacion) {
                    $link = $publicacion->ampliar == 1 ? Yii::app()->createAbsoluteUrl('/publicaciones/' . $publicacion->publicacion_id) : false;
                    $image = $publicacion->imagen_destacada != '' && file_exists(Yii::app()->basePath . '/../../repository/' . $publicacion->imagen_destacada)
                        ? Commons::image('repository/' . $publicacion->imagen_destacada, 270, 159)
                        : Yii::app()->theme->baseUrl . '/images/temp/block-image-04-270x159.jpg';
                    ?>
                    <div>
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