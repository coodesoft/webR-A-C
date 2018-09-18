<?php
$this->pageTitle=Yii::app()->name . ' :: Direcciones';
?>

<!-- Breadcrumbs -->
<section class="container">
    <nav class="breadcrumbs">
        <a href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">Home</a>
        <span class="divider">›</span> Direcciones </nav>
</section>
<!-- //end Breadcrumbs -->

<!-- Two column content -->
<section class="container">
    <div class="row">
        <section class="col-sm-12 col-md-12 col-lg-12">
            <section>
                <ul class="nav nav-pills">
                    <li role="presentation"><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/profile/edit') ?>">Perfil</a></li>
                    <li role="presentation"><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/profile/changepassword') ?>">Cambiar contraseña</a></li>
                    <li role="presentation"><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/profile/pedidos') ?>">Pedidos</a></li>
                    <li role="presentation" class="active"><a href="<?php echo Yii::app()->createAbsoluteUrl('/direcciones') ?>">Direcciones</a></li>
                </ul>
            </section>
            <section class="container-with-large-icon login-form">
                <div class="wrap">
                    <h3>NUEVA DIRECCIÓN</h3>

                    <?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
                </div>
            </section>
        </section>
    </div>
</section>
<!-- //end Two columns content -->
