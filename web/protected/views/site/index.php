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

<!-- Two columns content -->
<section class="container content">
    <div class="row">
        <?php //$this->widget('LeftColumn') ?>
        <?php $this->widget('RightColumn') ?>
    </div>
</section>