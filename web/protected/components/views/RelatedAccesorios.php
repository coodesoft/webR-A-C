<?php
if (count($accesorios)) {
    ?>
    <!-- Related Products -->
    <section class="col-sm-12 col-md-3 col-lg-3 slider-products module">
        <h3>Accesorios relacionados</h3>
        <div class="products-widget jcarousel-skin-previews jcarousel-skin-previews-4 vertical">
            <ul class="slides">
                <?php
                foreach ($accesorios as $accesorio) {
                    $producto = Productos::getProductInfo($accesorio->accesorio_categoria_id, $accesorio->accesorio_producto_id);
                    if ($producto === null) {
                        continue;
                    }
                    ?>
                    <li>
                        <div class="product">
                            <a
                                href="<?php echo Yii::app()->createAbsoluteUrl('/productos/detail/' . $accesorio->accesorio_categoria_id . '/' . $producto->producto_id) ?>"
                                class="preview-image">
                                <img
                                    class="img-responsive product_activ"
                                    src="<?php echo Yii::app()->baseUrl . '/../repository/' . $producto->foto ?>"
                                    alt="">
                            </a>
                            <p class="name">
                                <a
                                    href="<?php echo Yii::app()->createAbsoluteUrl('/productos/detail/' . $accesorio->accesorio_categoria_id . '/' . $producto->producto_id) ?>"
                                    class="preview-image"><?php echo $producto->etiqueta ?>
                                </a>
                            </p>
                            <span
                                class="price new">$<?php echo Commons::formatPrice($producto->precio[15]['precio']) ?></span>
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