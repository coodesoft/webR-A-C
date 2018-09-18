<?php
if (count($destacados)) {
    ?>
    <section">
        <h3>Más vendidos</h3>
        <!-- Products list -->
        <div class="">
            <div class="product-carousel">
                <?php
                foreach ($destacados as $novedad)
                    $this->widget('ProductCardMin',['item'=>$novedad]);
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
