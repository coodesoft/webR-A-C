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
<?php $this->widget('HomeSliderRight', array(
    'extraCss' => 'services-block hidden-xs noBorders',
    'tipos' => array(Slides::SLIDES_SECUNDARIO),
    'height' => 156,
    'zc' => 0
)) ?>

<!--section class="services-block hidden-xs">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-lg-4 divider-right">
                <a href="<?php echo Yii::app()->createAbsoluteUrl('/secciones/enviosgratis') ?>" class="item">
                    <span class="icon icon-airplane-2"></span>
                    <span class="title">Envios Gratis</span>
                    <span class="description">en compras mayores a $1200</span>
                </a>
            </div>
            <div class="col-xs-12 col-sm-4 col-lg-4 divider-right">
                <a href="<?php echo Yii::app()->createAbsoluteUrl('/secciones/horarios') ?>" class="item">
                    <span class="icon icon-clock"></span>
                    <span class="title">Horarios</span>
                    <span class="description">Todos los dias de 8 a 20</span>
                </a>
            </div>
            <div class="col-xs-12 col-sm-4 col-lg-4">
                <a href="<?php echo Yii::app()->createAbsoluteUrl('/secciones/soporte24') ?>" class="item">
                    <span class="icon icon-umbrella "></span>
                    <span class="title">Soporte 24 Hs </span>
                    <span class="description">Respondemos todos tus consultas</span>
                </a>
            </div>
        </div>
    </div>
</section-->