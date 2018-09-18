<!-- Breadcrumbs -->
<section class="container">
    <nav class="breadcrumbs">
        <a href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">Home</a>
        <span class="divider">›</span> Ingresar </nav>
</section>
<!-- //end Breadcrumbs -->

<!-- Two column content -->
<section class="container">
    <div class="row">
      <section class="col-lg-4">
          <section class="container-with-large-icon login-form">
              <div class="large-icon"><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/large-icon-user.png" alt=""></div>
              <div class="wrap">
                  <h3>INICIO SOCIAL</h3>
                  <p>También podrás registrarte o ingresar con tu red social.</p>
                  <br />
              </div>
              <div class="lgform-bottom">
                <?php echo SocialConnectModule::getHTMLLoginButton('google'); ?>
                <?php echo SocialConnectModule::getHTMLLoginButton('facebook'); ?>
              </div>
          </section>
      </section>

        <section class="col-lg-4">
            <section class="container-with-large-icon login-form">
                <div class="large-icon"><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/large-icon-user.png" alt=""></div>
                <div class="wrap">
                    <h3>NUEVO USUARIO</h3>
                    <p>Creando una cuenta vas a poder comprar de manera rápida y segura, estar al día con las ofertas, y tener un seguimiento de tus pedidos.</p>
                    <br />
                </div>
                <div class="lgform-bottom">
                  <?php echo CHtml::link(UserModule::t("Register"),Yii::app()->getModule('user')->registrationUrl, ['class' => 'btn btn-mega logIn-btn']); ?>
                </div>
            </section>
        </section>

        <section class="col-lg-4">
            <section class="container-with-large-icon login-form">
                <div class="large-icon"><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/large-icon-lock.png" alt=""></div>
                <?php echo CHtml::beginForm(); ?>
                <div class="wrap">
                    <h3>YA SOS USUARIO</h3>
                        <div class="form-group">
                            <?php echo PHtml::errorSummary($model); ?>
                        </div>
                        <div class="form-group">
                            <?php echo CHtml::activeLabelEx($model,'username'); ?>
                            <?php echo CHtml::activeTextField($model,'username', ['class' => 'form-control']) ?>
                        </div>
                        <div class="form-group">
                            <?php echo CHtml::activeLabelEx($model,'password'); ?>
                            <?php echo CHtml::activePasswordField($model,'password', ['class' => 'form-control']) ?>
                        </div>
                        <div class="form-group">
                            <?php echo CHtml::activeCheckBox($model,'rememberMe'); ?>
                            <?php echo CHtml::activeLabelEx($model,'rememberMe'); ?>
                        </div>
                        <div class="form-link">
                            <?php echo CHtml::link(UserModule::t("Lost Password?"),Yii::app()->getModule('user')->recoveryUrl); ?>
                        </div>
                </div>
                <div class="lgform-bottom">
                  <?php echo CHtml::submitButton(UserModule::t("Login"),['class' => 'btn btn-mega logIn-btn']); ?>
                  <?php echo CHtml::endForm(); ?>
                </div>
            </section>
        </section>
    </div>
</section>
<!-- //end Two columns content -->

<?php
$form = new CForm([
    'elements'=>[
        'username'=>[
            'type'=>'text',
            'maxlength'=>32,
        ],
        'password'=>[
            'type'=>'password',
            'maxlength'=>32,
        ],
        'rememberMe'=>[
            'type'=>'checkbox',
        ]
    ],

    'buttons'=>[
        'login'=>[
            'type'=>'submit',
            'label'=>'Login',
        ],
    ],
], $model);

?>
