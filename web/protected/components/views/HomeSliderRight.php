<?php
if (!$mini) {
    ?>
    <section class="<?php echo $extraCss ?> container itemsNoPadding" style="height: 200px;">
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
                                    src="<?php echo Commons::image('repository/' . $slide->imagen, $width, $height, $zc); ?>"
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
} else {
    ?>
    <div class="<?php echo $extraCss ?> mini-banner-slides">
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
                                    src="<?php echo Commons::image('repository/' . $slide->imagen, $width, $height, $zc); ?>"
                                    alt="">
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </section>
        <!-- //end Slider cuerpo -->
    </div>
<?php
}
?>