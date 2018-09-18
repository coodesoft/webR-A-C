<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle = 'Contacto :: ' . Yii::app()->name;

?>

<!-- Master Wrap : starts -->

<section class="mastwrap page-top-space">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'contact-form',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
	)); ?>

		<div class="container-fluid" >
		  	<div class="row">
			    <article class="col-md-12 text-center contact-bg page-bg parallax">
			      	<h1 class="main-heading font2 white"><span>Contacto</span></h1>
			    </article>
		  	</div>
		</div>

		<section class="contact-wallpaper parallax offwhite-bg" >
			<div class="container">
			  	<div class="row">
					<article class="col-md-6 dark-bg text-center contact-dual-panel">
			      		<div id="contact-form-wrap">
		                  	<div class="contact-item pad-common ">
		                    	<article>
		                        	<?php echo $form->textField($model,'name', array('class'=>'form-control border-form', 'placeholder'=>'Nombre y apellido')); ?>
									<?php echo $form->error($model,'name'); ?>
		                    	</article>
		                    	<article>
		                           	<?php echo $form->textField($model,'email', array('class'=>'form-control border-form', 'placeholder'=>'Email')); ?>
									<?php echo $form->error($model,'email'); ?>
		                     	</article>
		                     	<article>
		                           	<?php echo $form->textField($model,'phone', array('class'=>'form-control border-form', 'placeholder'=>'Teléfono')); ?>
									<?php echo $form->error($model,'phone'); ?>
		                     	</article>
		                     	<article>
		                          	<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50, 'class'=>'form-control border-form', 'placeholder'=>'Su mensaje')); ?>
									<?php echo $form->error($model,'body'); ?>
		                      		<div class="btn-wrap text-left">
		                      			<?php echo CHtml::submitButton('Enviar',array('class'=>'btn btn-expose btn-expose-white', 'id' => 'submit')); ?>
		                      		</div>
		                    	</article>
		                  	</div>
			            </div>
			    	</article>
			    	<article class="col-md-6 text-center white-bg contact-dual-panel">
			      		<div class="valign">
				          <h1 class="email-heading color font2"><span>info@coquipendino.com</span></h1>
				          <div class="separator"><img class="img-responsive" alt="" title="" src="images/separator/02-white.png"/></div>
				          <h6 class="dark font3">Ov Lagos 2478 , Rosario S2000FNC.</h6>
				          <h6 class="dark font3">Santa Fe, Argentina.</h6>
				          <h6 class="dark font2">Tel: +54 (0341) 4317838</h6>
			      		</div>
			    	</article>
			  	</div>
			</div>
		</section>
	<?php $this->endWidget(); ?>
</section>
<!-- Master Wrap : ends -->