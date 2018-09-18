<!-- Breadcrumbs -->
<section class="container">
    <nav class="breadcrumbs">
        <a href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">Home</a>
        <span class="divider">›</span> Login </nav>
</section>
<!-- //end Breadcrumbs -->

<!-- Two column content -->
<section class="container">
    <div class="row">
        <section class="col-sm-6 col-md-6 col-lg-6">
            <section class="container-with-large-icon login-form">
                <div class="large-icon"><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/large-icon-user.png" alt=""></div>
                <div class="wrap">
                    <h3>NUEVO USUARIO</h3>
                    <p>Creando una cuenta vas a poder comprar de manera rápida y segura, estar al día con las ofertas, y tener un seguimiento de tus pedidos.</p>
                    <br />
                    <?php echo CHtml::link(UserModule::t("Register"),Yii::app()->getModule('user')->registrationUrl, array('class' => 'btn btn-mega')); ?>
                </div>
            </section>
        </section>
        <section class="col-sm-6 col-md-6 col-lg-6">
            <section class="container-with-large-icon login-form">
                <div class="large-icon"><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/large-icon-lock.png" alt=""></div>
                <div class="wrap">
                    <h3>YA SOS USUARIO</h3>
                    <?php echo CHtml::beginForm(); ?>
                        <div class="form-group">
                            <?php echo PHtml::errorSummary($model); ?>
                        </div>
                        <div class="form-group">
                            <?php echo CHtml::activeLabelEx($model,'username'); ?>
                            <?php echo CHtml::activeTextField($model,'username', array('class' => 'form-control')) ?>
                        </div>
                        <div class="form-group">
                            <?php echo CHtml::activeLabelEx($model,'password'); ?>
                            <?php echo CHtml::activePasswordField($model,'password', array('class' => 'form-control')) ?>
                        </div>
                        <div class="form-group">
                            <?php echo CHtml::activeCheckBox($model,'rememberMe'); ?>
                            <?php echo CHtml::activeLabelEx($model,'rememberMe'); ?>
                        </div>
                        <div class="form-link">
                            <?php echo CHtml::link(UserModule::t("Lost Password?"),Yii::app()->getModule('user')->recoveryUrl); ?>
                        </div>
                        <?php echo CHtml::submitButton(UserModule::t("Login"), array('class' => 'btn btn-mega')); ?>
                    <?php echo CHtml::endForm(); ?>
                </div>
            </section>
        </section>
    </div>
</section>
<!-- //end Two columns content -->

<?php
$form = new CForm(array(
    'elements'=>array(
        'username'=>array(
            'type'=>'text',
            'maxlength'=>32,
        ),
        'password'=>array(
            'type'=>'password',
            'maxlength'=>32,
        ),
        'rememberMe'=>array(
            'type'=>'checkbox',
        )
    ),

    'buttons'=>array(
        'login'=>array(
            'type'=>'submit',
            'label'=>'Login',
        ),
    ),
), $model);
?>