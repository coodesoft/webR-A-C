<?php
$this->pageTitle=Yii::app()->name . ' :: '.UserModule::t("Change password");
?>

<!-- Breadcrumbs -->
<section class="container">
    <nav class="breadcrumbs">
        <a href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">Home</a>
        <span class="divider">›</span> <?php echo UserModule::t("Change password") ?> </nav>
</section>
<!-- //end Breadcrumbs -->

<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
    <section class="content-row">
        <div class="grey-container">
            <div class="container">
                <div class="col-lg-9"><?php echo Yii::app()->user->getFlash('profileMessage'); ?></div>
                <div class="col-lg-3"> <a class="btn btn-mega btn-lg" href="<?php echo Yii::app()->createAbsoluteUrl('/user/profile') ?>">IR A PERFIL</a></div>
            </div>
        </div>
    </section>
<?php else: ?>
    <!-- Two column content -->
    <section class="container">
        <div class="row">
            <section class="col-sm-12 col-md-12 col-lg-12">
                <section>
                    <ul class="nav nav-pills">
                        <li role="presentation"><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/profile/edit') ?>">Perfil</a></li>
                        <li role="presentation" class="active"><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/profile/changepassword') ?>">Cambiar contraseña</a></li>
                        <li role="presentation"><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/profile/pedidos') ?>">Pedidos</a></li>
                        <li role="presentation"><a href="<?php echo Yii::app()->createAbsoluteUrl('/direcciones') ?>">Direcciones</a></li>
                    </ul>
                </section>
                <section class="container-with-large-icon login-form">
                    <div class="large-icon"><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/large-icon-lock.png" alt=""></div>
                    <div class="wrap">
                        <h3>FORMULARIO DE CAMBIO DE CONTRASEÑA</h3>
                        <?php $form=$this->beginWidget('PActiveForm', array(
                            'id'=>'changepassword-form',
                            'enableAjaxValidation'=>true,
                            'clientOptions'=>array(
                                'validateOnSubmit'=>true,
                            ),
                        )); ?>
                        <?php echo $form->errorSummary($model); ?>
                        <div class="form-group">
                            <?php echo $form->labelEx($model,'oldPassword'); ?>
                            <?php echo $form->passwordField($model,'oldPassword', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model,'oldPassword'); ?>
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
                            <?php echo CHtml::submitButton(UserModule::t("Save"), array('class' => 'btn btn-mega')); ?>
                        </div>
                        <?php $this->endWidget(); ?>
                    </div>
                </section>
            </section>
        </div>
    </section>
    <!-- //end Two columns content -->
<?php endif; ?>