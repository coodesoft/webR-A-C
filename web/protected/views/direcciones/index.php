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
                    <h3>DIRECCIONES</h3>

                    <div class="row text-right">
                        <a class="btn btn-mega" href="<?php echo Yii::app()->createAbsoluteUrl('/direcciones/create') ?>">Nueva</a>
                        <br /><br />
                    </div>

                    <div class="row pedidosListHeader">
                        <div class="col-sm-1"><strong>Identificación</strong></div>
                        <div class="col-sm-2"><strong>Destinatario</strong></div>
                        <div class="col-sm-2"><strong>Domicilio</strong></div>
                        <div class="col-sm-2"><strong>Provincia</strong></div>
                        <div class="col-sm-2"><strong>Ciudad</strong></div>
                        <div class="col-sm-1"><strong>C. Postal</strong></div>
                        <div class="col-sm-1"><strong>Predet.</strong></div>
                        <div class="col-sm-1"></div>
                    </div>
                    <?php
                    foreach ($direcciones as $direccion) {
                        ?>
                        <div class="row pedidosListDetail">
                            <div class="col-sm-1"><?php echo $direccion->identificacion ?></div>
                            <div class="col-sm-2"><?php echo $direccion->fullName ?></div>
                            <div class="col-sm-2"><?php echo $direccion->fullDomicilio ?></div>
                            <div class="col-sm-2"><?php echo $direccion->provincia->provincia ?></div>
                            <div class="col-sm-2"><?php echo $direccion->ciudad->ciudad ?></div>
                            <div class="col-sm-1"><?php echo $direccion->cpostal ?></div>
                            <div class="col-sm-1"><?php echo $direccion->predeterminada == 1 ? 'SI' : 'NO' ?></div>
                            <div class="col-sm-1"><a href="<?php echo Yii::app()->createAbsoluteUrl('/direcciones/update/'.$direccion->cliente_direccion_id) ?>"><span class="icon-edit"></span></a></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </section>
        </section>
    </div>
</section>
<!-- //end Two columns content -->
