<?php $this->pageTitle=Yii::app()->name . ' :: '.UserModule::t("Registration"); ?>

<!-- Breadcrumbs -->
<section class="container">
    <nav class="breadcrumbs">
        <a href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">Home</a>
        <span class="divider">›</span> <?php echo UserModule::t("Registration") ?> </nav>
</section>
<!-- //end Breadcrumbs -->

<section class="content-row">
    <div class="grey-container">
        <div class="container">
            <div class="col-lg-9"><?php echo $content; ?></div>
            <div class="col-lg-3"> <a class="btn btn-mega btn-lg" href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">EMPEZAR A COMPRAR!</a></div>
        </div>
    </div>
</section>