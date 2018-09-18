<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' :: Acceso Clientes';

?>

<div class="title-clientes">Acceso Clientes</div>

<?php
$this->renderPartial('_accesoClientes',array('clientesPage'=>true));
?>

<div class="title-pass">Solicite su contraseña</div>

<div class="wrapper">
<div class="container">
<div class="row">

<div class="col-md-2 col-sm-2 animated"></div>

<div class="col-md-8 col-sm-8 animated">

<div class="texto-pass">
Para acceder a nuestra colección completa de productos, solicite su alta<br>
de cliente ingresando sus datos. Lo contactaremos de nuestro departamento<br>
comercial para chequear sus datos a la brevedad.
</div>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'clientes-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<?php if(Yii::app()->user->hasFlash('contactClientes')): ?>
	<div class="row form-group">
		<div class="alert alert-success" style="display: block;">
		  	<button type="button" class="close" data-dismiss="alert">&times;</button>
		    <?php echo Yii::t('strings','El email ha sido enviado. Nos pondremos en contacto a la brevedad.'); ?>
		</div>
	</div>
	<?php endif; ?>

	<div class="row form-group">
		<?php echo $form->textField($model,'name', array('class'=>'form-control', 'placeholder'=>'Nombre y apellido')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row form-group">
		<?php echo $form->textField($model,'email', array('class'=>'form-control', 'placeholder'=>'Email')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row form-group">
		<?php echo $form->textField($model,'cuit',array('size'=>11,'maxlength'=>11, 'class'=>'form-control', 'placeholder'=>'CUIT')); ?>
		<?php echo $form->error($model,'cuit'); ?>
	</div>

	<div class="row form-group">
		<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50, 'class'=>'form-control', 'placeholder'=>'Su mensaje')); ?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<div class="row form-group text-center">
		<?php echo CHtml::submitButton('Enviar',array('class'=>'button button-main')); ?>
	</div>

<?php $this->endWidget(); ?>

<br><br>

</div>

<div class="col-md-2 col-sm-2 animated"></div>

</div> 
</div>
</div>