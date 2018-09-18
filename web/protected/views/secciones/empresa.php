<?php
/* @var $this SeccionesController */
/* @var $model Secciones */

$this->pageTitle = 'Nuestra empresa :: ' . Yii::app()->name;
?>

<!-- Master Wrap : starts -->
<section class="mastwrap page-top-space">
    <div class="container-fluid">
        <div class="row">
            <article class="col-md-12 text-center about-bg page-bg parallax">
              <h1 class="main-heading font2 white"><span>Nuestra empresa</span></h1>
            </article>
        </div>
    </div>

    <div class="container">
        <div class="row add-top">
            <article class="col-md-10 col-md-offset-1 text-center">
              <h1 class="super-heading grey font2"><span>Traiga su boceto y convertiremos su proyecto en realidad.</span></h1>
              <div class="separator"><img alt="" title="" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/separator/01-white.png"/></div>
            </article>
        </div>

        <div class="row add-bottom">
            <article class="col-md-10 col-md-offset-1 text-center">
                <?php echo $empresa1->texto_seccion; ?>
            </article>
        </div>
    </div>

    <?php echo $this->renderPartial('/shared/about'); ?>

    <?php echo $this->renderPartial('/shared/workWithUs', array('colorClass' => 'offwhite-bg', 'separatorImg' => '02-color.png', 'btnExposeClass' => 'btn-expose-dark')); ?>

</section>
<!-- Master Wrap : ends -->