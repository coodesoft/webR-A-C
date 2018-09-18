<div class="formas_envio col-sm-6">
<?php
echo $this->widget('BootstrapRadioList',[
    'radioGroup' => 'formas_envio',
    'radios'  => PedidoOnline::getFormasEnvioData(),
],true);
?>
</div>

<div class="bodegas col-sm-6">
<h6>Sucursales:</h6>
<?php
echo $this->widget('BootstrapRadioList',[
    'radioGroup' => 'bodegas',
    'radios'  => PedidoOnline::getBodegasRetiroData(),
],true)
?>
</div>