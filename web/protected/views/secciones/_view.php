<?php
/* @var $this SeccionesController */
/* @var $data Secciones */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_seccion')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_seccion), array('view', 'id'=>$data->id_seccion)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('titulo_seccion')); ?>:</b>
	<?php echo CHtml::encode($data->titulo_seccion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('texto_seccion')); ?>:</b>
	<?php echo CHtml::encode($data->texto_seccion); ?>
	<br />


</div>