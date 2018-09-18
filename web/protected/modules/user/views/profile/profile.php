<?php
$this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Perfil");
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
                    <li role="presentation" class="active"><a href="#">Perfil</a></li>
                    <li role="presentation"><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/profile/changepassword') ?>">Cambiar password</a></li>
                    <li role="presentation"><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/profile/pedidos') ?>">Pedidos</a></li>
                </ul>
            </section>
            <section>

            </section>
        </section>
    </div>
</section>
<!-- //end Two columns content -->