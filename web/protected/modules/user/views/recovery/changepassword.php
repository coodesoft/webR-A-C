<?php
$this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Change password");
?>

<!-- Breadcrumbs -->
<section class="container">
    <nav class="breadcrumbs">
        <a href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">Home</a>
        <span class="divider">›</span> <?php echo UserModule::t("Change password") ?> </nav>
</section>
<!-- //end Breadcrumbs -->

<!-- Two column content -->
<section class="container">
    <div class="row">
        <section class="col-sm-12 col-md-12 col-lg-12">
            <section class="container-with-large-icon login-form">
                <div class="large-icon"><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/large-icon-lock.png" alt=""></div>
                <div class="wrap">
                    <h3>FORMULARIO DE CAMBIO DE CONTRASEÑA</h3>
                    <?php echo CHtml::beginForm(); ?>
                    <?php echo PHtml::errorSummary($form); ?>
                    <div class="form-group">
                        <?php echo CHtml::activeLabelEx($form,'password'); ?>
                        <?php echo CHtml::activePasswordField($form,'password', array('class' => 'form-control')); ?>
                        <p class="hint">
                            <?php echo UserModule::t("Minimal password length 4 symbols."); ?>
                        </p>
                    </div>
                    <div class="form-group">
                        <?php echo CHtml::activeLabelEx($form,'verifyPassword'); ?>
                        <?php echo CHtml::activePasswordField($form,'verifyPassword', array('class' => 'form-control')); ?>
                    </div>
                    <div class="form-group">
                        <?php echo CHtml::submitButton(UserModule::t("Save"), array('class' => 'btn btn-mega')); ?>
                    </div>
                    <?php echo CHtml::endForm(); ?>
                </div>
            </section>
        </section>
    </div>
</section>
<!-- //end Two columns content -->