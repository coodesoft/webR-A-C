<?php
/* @var $this ProductosController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle=Yii::app()->name . ' :: Productos';

?>
<div class="title">Productos</div>

<?php $this->renderPartial('_barra',array('selected'=>'hombre')); ?>

<div class="rev-slider-full">
<div class="rev-slider-banner-full  rev-slider-full">
<ul>
<li data-transition="fade" data-slotamount="14"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/placeholders/hombre01.png" alt=""></li>                          
<li data-transition="fade" data-slotamount="14"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/placeholders/hombre02.png" alt=""></li>
<li data-transition="fade" data-slotamount="14"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/placeholders/hombre03.png" alt=""></li>                          
<li data-transition="fade" data-slotamount="14"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/placeholders/hombre04.png" alt=""></li>
<li data-transition="fade" data-slotamount="14"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/placeholders/hombre05.png" alt=""></li>                          
<li data-transition="fade" data-slotamount="14"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/placeholders/hombre06.png" alt=""></li>
</ul>
</div>
</div>

<?php
$this->renderPartial('/site/_accesoClientes');
?>