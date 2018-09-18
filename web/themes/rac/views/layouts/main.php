<!DOCTYPE html>
<html lang="es">
<head>
    <?php
    $cs=Yii::app()->clientScript;
    $cs->scriptMap=['jquery.js'=>false,];
    ?>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-1.10.2.min.js"></script>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <meta charset="utf-8">
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <?= SocialConnectModule::getMetaTags('google'); ?>
    <link rel="icon" href="<?php echo Yii::app()->theme->baseUrl . '/images/racIconMenu.png' ?>" type="image/x-icon" />
    <!-- CSS preloader -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/loader.css" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/megatron-template.css" rel="stylesheet">
    <!-- CSS modules -->
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/icomoon.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/fontello.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/flexslider.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/jcarousel.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/owl.carousel.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/owl.theme.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/cloudzoom.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/sfmenu.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/isotope.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/smoothness/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/jquery.fancybox.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/hoverfold.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/overrides.css" rel="stylesheet">

    <script>
    var BASE_URL = "<?php echo Yii::app()->baseUrl ?>";
    var BASE_PATH = "<?php echo Yii::app()->basePath ?>";
    var THEME_BASE_URL = "<?php echo Yii::app()->theme->baseUrl ?>";
    var THEME_BASE_PATH = "<?php echo Yii::app()->theme->basePath ?>";
    </script>
</head>

<body class="responsive">

<?php $this->widget('PLoader') ?>
<?php $this->widget('MainMenu') ?>
        <?php echo $content; ?>
        <?php $this->widget('SocialNav') ?>
        <?php $this->widget('Footer') ?>
        <div id="outer-overlay"></div>
    </div>
</div>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/html5shiv.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/respond.min.js"></script>
<![endif]-->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.easing.1.3.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.mousewheel.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootbox.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.flexslider.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/owl.carousel.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.jcarousel.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/cloudzoom.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.isotope.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.parallax.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.fancybox.js?v=2.1.5"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.inview.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/hoverIntent.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/superfish.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/supersubs.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.plugin.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.countdown.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.carouFredSel-6.2.1-packed.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/notify.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/megatron.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/Commons.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/Cart.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/Profile.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/Checkout.js"></script>

<script src='https://apis.google.com/js/platform.js?onload=googleOnLoad' async defer></script>

<script src='https://connect.facebook.net/en_US/sdk.js' async defer></script>

<?php
  echo SocialConnectModule::getJavaScript('facebook');
  echo SocialConnectModule::getJavaScript('google');
 ?>
</body>
</html>
