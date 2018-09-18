<?php
if (count($productos)) {
    ?>
    <!-- Related Products -->
    <section class="col-sm-12 col-md-3 col-lg-3 slider-products module">
        <h3>Productos relacionados</h3>
        <div class="products-widget jcarousel-skin-previews jcarousel-skin-previews-4 vertical">
            <ul class="slides">
                <?php
                foreach ($productos as $producto) {
                    ?>
                    <li>
                        <div class="product">
                            <a
                                href="<?php echo Yii::app()->createAbsoluteUrl('/productos/detail/' . $producto->categoria->categoria_id . '/' . $producto->producto_id) ?>"
                                class="preview-image">
                                <img
                                    class="img-responsive product_activ"
                                    src="<?php echo Yii::app()->baseUrl . '/../repository/' . $producto->foto ?>"
                                    alt="">
                            </a>
                            <p class="name">
                                <a
                                    href="<?php echo Yii::app()->createAbsoluteUrl('/productos/detail/' .  $producto->categoria->categoria_id . '/' . $producto->producto_id) ?>"
                                    class="preview-image"><?php echo $producto->etiqueta ?>
                                </a>
                            </p>
                            <span
                                class="price new">$<?php echo Commons::formatPrice($producto->precio[ProductosPrecios::$PRECIO_RELACIONADO_ID]['precio']) ?></span>
                        </div>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </section>
    <!-- //end Related Products -->
    <?php
}
?>