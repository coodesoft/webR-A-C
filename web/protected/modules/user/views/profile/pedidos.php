<?php
$this->pageTitle=Yii::app()->name . ' :: Pedidos';
?>

<!-- Breadcrumbs -->
<section class="container">
    <nav class="breadcrumbs">
        <a href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">Home</a>
        <span class="divider">›</span> <?php echo UserModule::t("Pedidos") ?> </nav>
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
                    <li role="presentation" class="active"><a href="<?php echo Yii::app()->createAbsoluteUrl('/user/profile/pedidos') ?>">Pedidos</a></li>
                    <li role="presentation"><a href="<?php echo Yii::app()->createAbsoluteUrl('/direcciones') ?>">Direcciones</a></li>
                </ul>
            </section>
            <section class="container-with-large-icon login-form">
                <div class="wrap">
                    <h3>HISTORIAL DE PEDIDOS</h3>

                    <div class="row pedidosListHeader">
                        <div class="col-sm-3"><strong>N° pedido</strong></div>
                        <div class="col-sm-2"><strong>Fecha</strong></div>
                        <div class="col-sm-2 text-right"><strong>Total</strong></div>
                        <div class="col-sm-3 text-right"><strong>Estado</strong></div>
                        <div class="col-sm-2">&nbsp;</div>
                    </div>
                    <?php
                    foreach ($ventas as $venta) {
                        ?>
                        <div class="row pedidosListDetail">
                            <div class="col-sm-3"><?php echo $venta['id'] ?></div>
                            <div class="col-sm-2"><?php echo $venta['fecha'] ?></div>
                            <div class="col-sm-2 text-right">$<?php echo Commons::formatPrice($venta['total']) ?></div>
                            <div class="col-sm-3 text-right"><?php echo $venta['estado'] ?></div>
                            <div class="col-sm-2 text-right">
                                <a href="#" class="icon icon-plus toggleDetailPedidos" data-id="<?php echo $venta['id'] ?>"></a>
                                <a href="#" class="icon icon-minus toggleDetailPedidos" data-id="<?php echo $venta['id'] ?>" style="display: none;"></a>
                            </div>
                        </div>
                        <div style="display: none; padding: 0 5px;" class="row pedidoDetailRow_<?php echo $venta['id'] ?>">
                            <table class="table table-striped">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-right">Cantidad</th>
                                    <th class="text-right">Total</th>
                                </tr>
                                <?php
                                foreach ($venta['items'] as $item) {
                                    ?>
                                    <tr>
                                        <td><?php echo $item['item']; ?></td>
                                        <td class="text-right"><?php echo $item['quantity']; ?></td>
                                        <td class="text-right">$<?php echo Commons::formatPrice($item['price']); ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
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