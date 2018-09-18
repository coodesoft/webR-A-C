<?php
if (count($direcciones) != 0) {
    ?>
    <div class="col-sm-6">
        <p>
            <strong>
              <span class="entrega_identificacion"><?= $direcciones[0]->identificacion; ?></span>
            </strong>
        </p>
        <p>
            <span class="entrega_fullname"><?= $direcciones[0]->fullName; ?></span>
        </p>
        <p>
            <span class="entrega_domicilio"><?= $direcciones[0]->fullDomicilio; ?></span>
        </p>
        <p>
            <span class="entrega_ciudad"><?= $direcciones[0]->ciudad->ciudad; ?></span>
        </p>
        <p>
            <span class="entrega_provincia"><?= $direcciones[0]->provincia->provincia; ?></span>, <span class="entrega_postal"><?php echo $direcciones[0]->cpostal; ?></span>
        </p>
        <input type="hidden" value="<?= $direcciones[0]->cliente_direccion_id ?>" class="entrega_id" name="entrega_id" />
        <input type="hidden" value="<?= $direcciones[0]->ciudad_id ?>" class="localidad_entrega_id" name="localidad_entrega_id" />
    </div>
    <div class="col-sm-6">
        <h6>Seleccionar otra dirección</h6>

        <section class="slider-products module">
            <div class="products-widget jcarousel-skin-previews vertical">
                <ul class="slides">
                    <?php
                    foreach ($direcciones as $direccion) {
                        ?>
                        <li>
                            <div class="product">
                                <div class="pull-left">
                                    <p>
                                        <strong>
                                            <span><?= $direccion->identificacion; ?>, <span><?php echo $direccion->fullName; ?></span></span>
                                        </strong>
                                    </p>
                                    <p>
                                        <span><?= $direccion->fullDomicilio; ?></span>
                                    </p>
                                    <p>
                                        <span><?= $direccion->ciudad->ciudad; ?></span>, <span><?php echo $direccion->provincia->provincia; ?></span>, <span><?php echo $direccion->cpostal; ?></span>
                                    </p>
                                </div>
                                <div class="pull-right">
                                    <a  class="icon icon-checkmark-3 selectEntrega"
                                        href="#"
                                        title="Seleccionar"
                                        data-localidad_id="<?= $direccion->ciudad_id ?>"
                                        data-id="<?= $direccion->cliente_direccion_id ?>"
                                        data-identificacion="<?= $direccion->identificacion ?>"
                                        data-fullname="<?= $direccion->fullName ?>"
                                        data-domicilio="<?= $direccion->fullDomicilio ?>"
                                        data-provincia="<?= $direccion->provincia->provincia ?>"
                                        data-ciudad="<?= $direccion->ciudad->ciudad ?>"
                                        data-cpostal="<?= $direccion->cpostal ?>"
                                    >
                                    </a>
                                </div>
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <a href="<?= Yii::app()->createAbsoluteUrl('/direcciones/create?returnTo=productos/checkout') ?>" class="btn btn-mega btn-mega-alt pull-right">CREAR DIRECCIONES</a>
        </section>
    </div>
    <?php
} else {
    ?>
    <div class="col-md-12">
        <div class="icon-circle-sm active"><span class="icon icon-notice"></span></div>
        <h4>No tienes definidas direcciones</h4>
        <p>Debes definir direcciones de facturación / entrega para poder procesar tu pedido.</p>
        <a href="<?= Yii::app()->createAbsoluteUrl('/direcciones/create?returnTo=productos/checkout') ?>" class="btn btn-mega btn-mega-alt pull-right">CREAR DIRECCIONES</a>
    </div>
    <?php
}
?>
