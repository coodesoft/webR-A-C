<?php
$this->pageTitle=Yii::app()->name . ' :: '.UserModule::t("Editar perfil");
?>

<!-- Breadcrumbs -->
<section class="container">
    <nav class="breadcrumbs">
        <a href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">Home</a>
        <span class="divider">›</span> <?php echo UserModule::t("Profile") ?> </nav>
</section>
<!-- //end Breadcrumbs -->

<!-- Two column content -->
<section class="container">
    <div class="row">
        <section class="col-sm-12 col-md-12 col-lg-12">
            <section>
                <ul class="nav nav-pills">
                    <li role="presentation" class="active"><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/profile/edit') ?>">Perfil</a></li>
                    <li role="presentation"><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/profile/changepassword') ?>">Cambiar contraseña</a></li>
                    <li role="presentation"><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/profile/pedidos') ?>">Pedidos</a></li>
                    <li role="presentation"><a href="<?php echo Yii::app()->createAbsoluteUrl('/direcciones') ?>">Direcciones</a></li>
                </ul>
            </section>
            <section class="container-with-large-icon login-form">
                <div class="wrap">
                    <h3>FORMULARIO DE REGISTRO</h3>
                    <?php $form=$this->beginWidget('CActiveForm', array(
                        'id'=>'profile-form',
                        'enableAjaxValidation'=>true,
                        'htmlOptions' => array('enctype'=>'multipart/form-data'),
                    )); ?>
                    <?php echo $form->errorSummary(array($model,$profile)); ?>

                    <?php
                    $profileFields=$profile->getFields();
                    if ($profileFields) {
                        foreach($profileFields as $field) {
                            ?>
                            <div class="form-group">
                                <?php echo $form->labelEx($profile,$field->varname);

                                if ($widgetEdit = $field->widgetEdit($profile)) {
                                    echo $widgetEdit;
                                } elseif ($field->range) {
                                    echo $form->dropDownList($profile,$field->varname,Profile::range($field->range));
                                } elseif ($field->field_type=="TEXT") {
                                    echo $form->textArea($profile,$field->varname,array('rows'=>6, 'cols'=>50, 'class' => 'form-control'));
                                } else {
                                    echo $form->textField($profile,$field->varname,array('class' => 'form-control', 'size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
                                }
                                echo $form->error($profile,$field->varname); ?>
                            </div>
                            <?php
                        }
                    }
                    ?>

                    <div class="form-group">
                        <?php echo $form->labelEx($model,'email'); ?>
                        <?php echo $form->textField($model,'email',array('class' => 'form-control','size'=>60,'maxlength'=>128)); ?>
                        <?php echo $form->error($model,'email'); ?>
                    </div>

                    <div class="form-group">
                        <?php echo CHtml::submitButton($model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save'), array('class' => 'btn btn-mega')); ?>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </section>
        </section>
    </div>
</section>
<!-- //end Two columns content -->