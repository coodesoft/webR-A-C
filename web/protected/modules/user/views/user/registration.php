<?php
$this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Registration");
?>
<!-- Breadcrumbs -->
<section class="container">
    <nav class="breadcrumbs">
        <a href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">Home</a>
        <span class="divider">›</span> <?php echo UserModule::t("Registration") ?> </nav>
</section>
<!-- //end Breadcrumbs -->

<?php if(Yii::app()->user->hasFlash('registration')): ?>
    <section class="content-row">
        <div class="grey-container">
            <div class="container">
                <div class="col-lg-9"><?php echo Yii::app()->user->getFlash('registration'); ?></div>
                <div class="col-lg-3"> <a class="btn btn-mega btn-lg" href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">EMPEZAR A COMPRAR!</a></div>
            </div>
        </div>
    </section>
<?php else: ?>
    <!-- Two column content -->
    <section class="container">
        <div class="row">
            <section class="col-sm-12 col-md-12 col-lg-12">
                <section class="container-with-large-icon login-form">
                    <div class="large-icon"><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/large-icon-user.png" alt=""></div>
                    <div class="wrap">
                        <h3>FORMULARIO DE REGISTRO</h3>
                        <?php $form=$this->beginWidget('UActiveForm', array(
                            'id'=>'registration-form',
                            'enableAjaxValidation'=>true,
                            'disableAjaxValidationAttributes'=>array('RegistrationForm_verifyCode'),
                            'clientOptions'=>array(
                                'validateOnSubmit'=>true,
                            ),
                            'htmlOptions' => array('enctype'=>'multipart/form-data'),
                        )); ?>
                        <?php echo $form->errorSummary(array($model,$profile)); ?>
                        <div class="form-group">
                            <?php echo $form->labelEx($model,'username'); ?>
                            <?php echo $form->textField($model,'username', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model,'username'); ?>
                        </div>
                        <div class="form-group">
                            <?php echo $form->labelEx($model,'password'); ?>
                            <?php echo $form->passwordField($model,'password', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model,'password'); ?>
                            <p class="hint">
                                <?php echo UserModule::t("Minimal password length 4 symbols."); ?>
                            </p>
                        </div>
                        <div class="form-group">
                            <?php echo $form->labelEx($model,'verifyPassword'); ?>
                            <?php echo $form->passwordField($model,'verifyPassword', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model,'verifyPassword'); ?>
                        </div>
                        <div class="form-group">
                            <?php echo $form->labelEx($model,'email'); ?>
                            <?php echo $form->textField($model,'email', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model,'email'); ?>
                        </div>

                        <?php
                        $profileFields=$profile->getFields();
                        if ($profileFields) {
                            foreach($profileFields as $field) {
                                ?>
                                <div class="form-group">
                                    <?php echo $form->labelEx($profile,$field->varname); ?>
                                    <?php
                                    if ($widgetEdit = $field->widgetEdit($profile)) {
                                        echo $widgetEdit;
                                    } elseif ($field->range) {
                                        echo $form->dropDownList($profile,$field->varname,Profile::range($field->range));
                                    } elseif ($field->field_type=="TEXT") {
                                        echo$form->textArea($profile,$field->varname,array('rows'=>6, 'cols'=>50, 'class' => 'form-control'));
                                    } else {
                                        echo $form->textField($profile,$field->varname,array('class' => 'form-control', 'size' => 60,'maxlength' => (($field->field_size) ? $field->field_size : 255)));
                                    }
                                    ?>
                                    <?php echo $form->error($profile,$field->varname); ?>
                                </div>
                                <?php
                            }
                        }
                        ?>

                        <?php if (UserModule::doCaptcha('registration')): ?>
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'verifyCode'); ?>
                                <?php $this->widget('CCaptcha'); ?>
                                <?php echo $form->textField($model,'verifyCode', array('class' => 'form-control')); ?>
                                <?php echo $form->error($model,'verifyCode'); ?>

                                <p class="hint"><?php echo UserModule::t("Please enter the letters as they are shown in the image above."); ?>
                                    <br/><?php echo UserModule::t("Letters are not case-sensitive."); ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <?php echo CHtml::submitButton(UserModule::t("Register"), array('class' => 'btn btn-mega')); ?>
                        </div>
                        <?php $this->endWidget(); ?>
                    </div>
                </section>
            </section>
        </div>
    </section>
    <!-- //end Two columns content -->
<?php endif; ?>