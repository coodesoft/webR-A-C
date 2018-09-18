<?php
/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 05/07/16
 * Time: 23:51
 */
$this->pageTitle = Yii::app()->name . ' :: ' . 'Checkout';
?>
<!-- Breadcrumbs -->
<section class="container">
    <nav class="breadcrumbs">
        <a href="<?= Yii::app()->createAbsoluteUrl('/') ?>">Home</a>
        <span class="divider">›</span> Checkout
    </nav>
</section>
<!-- //end Breadcrumbs -->

<!-- Two column content -->
<section class="container">
    <div class="row">
        <section class="col-md-7 col-lg-7">
            <!-- Checkout -->
            <section class="content-box">
              <?php if($items->itemsCount != 0){ ?>
                <div class="checkout">
                    <div class="box">
                        <div class="checkout_detail">
                            <form role="form" method="POST">
                                <h3>REALIZAR PAGO</h3>

                                <?php if(Yii::app()->user->hasFlash('error')): ?>
                                    <div class="alert alert-danger">
                                        <p>No hay stock de alguno de los productos seleccionados. Se ha enviado un email automático a un administrador para que se ponga en contacto contigo.</p>
                                        <p>
                                            <input type="checkbox" name="consentCompraWithoutStock" id="consentCompraWithoutStock" value="0" />
                                            <label for="consentCompraWithoutStock">
                                                <strong>Entiendo los riesgos, quiero continuar la compra.</strong>
                                            </label>
                                        </p>
                                    </div>
                                <?php endif;

                                  $this->widget('BootstrapPanelGroup',[
                                    'panels' => [
                                      ['title' => ['text' => '<span>1</span> FORMA DE PAGO',],
                                        'content' => $this->widget('BootstrapRadioList',[
                                                        'radioGroup' => 'medio_pago',
                                                        'radios'  => PedidoOnline::getFormasPagoData(),
                                                      ],true),
                                        'id' => 'collapseOne',
                                        'class' => 'medio-pago',
                                        'active' => true],
                                      ['title' => ['text' => '<span>2</span> DATOS DE FACTURACIÓN',],
                                        'id' => 'collapseTwo',
                                        'content' => $this->renderPartial('_direcciones',['direcciones' => $direcciones],true)],
                                      ['title' => ['text' => '<span>3</span> DATOS DE RETIRO / ENTREGA',],
                                        'content' => $this->renderPartial('_direcciones_entrega',['direcciones' => $direcciones],true),
                                        'id' => 'collapseTree'],
                                      ['title' => ['text' => '<span>4</span> MEDIO DE ENTREGA',],
                                        'content' => $this->renderPartial('_bodegas_retiro',['bodegas' => $bodegasRetiro],true),
                                        'id' => 'collapseFour'],
                                    ],
                                    'id' => 'checkOut',
                                  ]);
                                ?>
                                <div class="divider divider-sm visible-sm  visible-xs"></div>
                                <div class="clearfix"><br></div>
                                <section class="pull-right">
                                    <input
                                        type="submit"
                                        class="btn btn-mega"
                                        value="REALIZAR PAGO"
                                        <?php if (count($direcciones) == 0) { ?>
                                            disabled="disabled"
                                            tooltip="Debes definir direcciones de facturación / entrega para procesar tu pedido."
                                            title="Debes definir direcciones de facturación / entrega para procesar tu pedido."
                                        <?php } ?>
                                    />
                                </section>
                            </form>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
              <?php } else {
                  $this->widget('EmptyShopCart',['class' => '']);
              } ?>
              <div class="clearfix"></div>
            </section>
            <!-- //end Shopping cart -->
        </section>
        <section class="col-md-5 col-lg-5">
            <?php
              $this->widget('ProductList',[
                'titulo' => 'TU PEDIDO',
                'items'  => $items,
                'costoEnvio'  => $costoEnvio,
                'costoEnvioRapido'  => PedidoOnline::COSTO_FORMAENVIO_ENVIO_RAPIDO
              ]);
            ?>
        </section>
    </div>
</section>
<!-- //end Two columns content -->
