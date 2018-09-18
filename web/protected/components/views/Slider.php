<!-- Slider -->
<section class="main-slider container">
    <div class="owl-slider-outer row">
        <div class="owl-slider">
            <?php
            foreach ($slides as $slide) {
                $link = $slide->enlace != '' ? $slide->enlace : 'javascript:;';
                ?>
                <div class="item">
                    <a href="<?php echo $link ?>">
                        <img
                            src="<?php echo Commons::image('repository/'.$slide->imagen, 1170, 290); ?>"
                            alt="">
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</section>