<section class="services-block hidden-xs">
    <div class="container">
        <div class="row">
            <?php $this->widget('HomeSliderRight', array(
                'extraCss' => 'col-xs-12 col-sm-4 col-lg-4 divider-right',
                'tipos' => array(Slides::SLIDES_MINI_1),
                'zc' => 1,
                'width' => 486,
                'height' => 144,
                'mini' => true
            )) ?>
            <?php $this->widget('HomeSliderRight', array(
                'extraCss' => 'col-xs-12 col-sm-4 col-lg-4 divider-right',
                'tipos' => array(Slides::SLIDES_MINI_2),
                'zc' => 1,
                'width' => 486,
                'height' => 144,
                'mini' => true
            )) ?>
            <?php $this->widget('HomeSliderRight', array(
                'extraCss' => 'col-xs-12 col-sm-4 col-lg-4',
                'tipos' => array(Slides::SLIDES_MINI_3),
                'zc' => 1,
                'width' => 486,
                'height' => 144,
                'mini' => true
            )) ?>
        </div>
    </div>
</section>