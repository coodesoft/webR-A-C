<?php
$this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Restore");
?>

<!-- Breadcrumbs -->
<section class="container">
    <nav class="breadcrumbs">
        <a href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">Home</a>
        <span class="divider">›</span> <?php echo UserModule::t("Restore") ?> </nav>
</section>
<!-- //end Breadcrumbs -->

<?php if(Yii::app()->user->hasFlash('recoveryMessage')): ?>
    <section class="content-row">
        <div class="grey-container">
            <div class="container">
                <div class="col-lg-9"><?php echo Yii::app()->user->getFlash('recoveryMessage'); ?></div>
                <div class="col-lg-3"> <a class="btn btn-mega btn-lg" href="<?php echo Yii::app()->createAbsoluteUrl('/user/login') ?>">IR A LOGIN</a></div>
            </div>
        </div>
    </section>
<?php else: ?>
    <!-- Two column content -->
    <section class="container">
        <div class="row">
            <section class="col-sm-12 col-md-12 col-lg-12">
                <section class="container-with-large-icon login-form">
                    <div class="large-icon"><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/large-icon-lock.png" alt=""></div>
                    <div class="wrap">
                        <h3>FORMULARIO DE RECUPERACIÓN DE CONTRASEÑA</h3>
                        <?php echo CHtml::beginForm(); ?>
                        <?php echo PHtml::errorSummary($form); ?>
                        <div class="form-group">
                            <?php echo CHtml::activeLabel($form,'login_or_email'); ?>
                            <?php echo CHtml::activeTextField($form,'login_or_email', array('class' => 'form-control')) ?>
                            <p class="hint"><?php echo UserModule::t("Please enter your login or email addres."); ?></p>
                        </div>
                        <div class="form-group">
                            <?php echo CHtml::submitButton(UserModule::t("Restore"), array('class' => 'btn btn-mega')); ?>
                        </div>
                        <?php echo CHtml::endForm(); ?>
                    </div>
                </section>
            </section>
        </div>
    </section>
    <!-- //end Two columns content -->
<?php endif; ?>