<?php
if (count($novedades)) {
    ?>
    <!-- Two columns content -->
    <section class="container">
        <div class="row">

            <!-- Right column -->
            <section class="col-sm-12 col-md-12 col-lg-12 content-center">

                <!-- Featured Products -->
                <section>
                    <h3>Novedades</h3>

                    <!-- Products list -->
                    <div class="">
                        <div class="product-carousel">
                        <?php
                        foreach ($novedades as $novedad) 
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

                <!-- slider cuerpo 2-->
                <?php $this->widget('HomeSliderRight', array(
                    'extraCss' => 'homeSlider services-block hidden-xs noBorders noPadding',
                    'tipos' => array(Slides::SLIDES_SECUNDARIO_2)
                )) ?>

                <?php
                $this->widget('HomeMasVendidos');
                ?>

                <!-- slider cuerpo 3-->
                <?php $this->widget('HomeSliderRight', array(
                    'extraCss' => 'homeSlider services-block hidden-xs noBorders noPadding',
                    'tipos' => array(Slides::SLIDES_SECUNDARIO_3)
                )) ?>

                <!-- On Sale Products -->
                <?php
                $this->widget('HomeOfertas');
                ?>
                <!-- //end On Sale Products -->

                <?php
                $this->widget('HomePublicaciones');
                ?>

            </section>
            <!-- //end Right column -->

        </div>
    </section>
    <!-- //end Two columns content -->
    <?php
}
?>
