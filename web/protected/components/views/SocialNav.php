<!-- Social navbar -->
<section class="content content-border nopad-xs social-widget">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-6 newsletter collapsed-block">
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12 ">
                        <h3>NEWSLETTER <a class="expander visible-xs" href="#TabBlock-1">+</a></h3>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-6 tabBlock" id="TabBlock-1">
                        <?php
                        if(!Yii::app()->user->hasFlash('success')) {
                            ?>
                            <p>Ingresa tu e-mail y recibi todas las novedades y promociones personalmente.</p>
                            <?php $form = $this->beginWidget('CActiveForm', array(
                                'id' => 'newsletter-form',
                                'enableClientValidation' => true,
                                'clientOptions' => array(
                                    'validateOnSubmit' => true,
                                ),
                            )); ?>
                            <div class="form-group input-control">
                                <button type="submit" class="button"><span class="icon-envelop"></span></button>
                                <?php echo $form->textField($model, 'email', array('class' => 'form-control', 'placeholder' => 'E-mail...')); ?>
                            </div>
                            <?php $this->endWidget(); ?>
                            <?php
                        } else {
                            ?>
                            <span>El email fue registrado en nuestras bases de datos. Recibirás todas nuestras novedades y ofertas.</span>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 col-md-3 col-lg-3 collapsed-block social-links">
                <h3>Seguinos tambien en nuestras redes sociales<a class="expander visible-xs" href="#TabBlock-2">+</a></h3>
                <div  class="tabBlock" id="TabBlock-2">
                    <ul class="find-us">
                        <li class="divider"><a href="https://www.facebook.com/RosarioalCosto/" class="animate-scale" target="_blank"><span class="icon icon-facebook-3"></span></a></li>
                        <li class="divider"><a href="https://twitter.com/rosarioalcosto1" class="animate-scale" target="_blank"><span class="icon icon-twitter-3" ></span></a></li>
                        <li class="divider"><a href="https://www.instagram.com/rosarioalcosto" class="animate-scale" target="_blank"><span class="icon icon-instagram"></span></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-3 col-md-3 col-lg-3 collapsed-block contact-block">
                <a href="<?php echo Yii::app()->createAbsoluteUrl('/site/contacto') ?>" class="dBlock">
                    <?php echo CHtml::image(Yii::app()->theme->baseUrl . '/images/contacto.png'); ?>
                </a>
            </div>
        </div>
    </div>
</section>
<!-- //end Social navbar -->