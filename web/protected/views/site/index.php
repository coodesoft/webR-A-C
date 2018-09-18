<?php
/* @var $this SiteController */
$this->pageTitle = Yii::app()->name;
?>

<!-- Slider -->
<?php $this->widget('Slider') ?>
<!-- //end Slider -->

<!-- Services -->
<?php $this->widget('Services') ?>
<!-- //end Services -->

<?php  $this->widget('HomeDestacados'); ?>

<?php $this->widget('HomeSliderRight', array(
    'extraCss' => 'services-block hidden-xs noBorders',
    'tipos' => array(Slides::SLIDES_SECUNDARIO),
    'height' => 200,
    'zc' => 0
)) ?>

<!-- Two columns content -->
<section class="container">
    <div class="row">
        <?php //$this->widget('LeftColumn') ?>
        <?php $this->widget('RightColumn') ?>
    </div>
</section>