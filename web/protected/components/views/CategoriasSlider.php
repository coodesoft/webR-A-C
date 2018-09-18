<?php
if (count($slides)) {
    ?>
    <section class="content-row" style="height: 200px;">
        <!-- Slider cuerpo-->
        <section class="owl-slider-outer slider-listing hidden-xs" style="margin-bottom: 50px !important;">
            <div class="owl-slider">
                <?php
                foreach ($slides as $slide) {
                    $link = $slide->enlace != '' ? $slide->enlace : 'javascript:;';
                    ?>
                    <div class="item">
                        <a href="<?php echo $link ?>">
                            <img
                                src="<?php echo Commons::image('repository/' . $slide->imagen, 1170, 208); ?>"
                                alt="">
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </section>
        <!-- //end Slider cuerpo -->
    </section>
    <?php
}
?>