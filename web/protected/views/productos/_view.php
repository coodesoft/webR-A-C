<?php
/* @var $this ProductosController */
/* @var $data Productos */

if($index==0){
    ?>
    <div class="row">
    <?php
}

if($index%4==0 && $index!=0){
    ?>
    </div>
    <div class="row">
    <?php
}

// recupero la imagen a mostrar
$img = ProductosFotos::getImagenPredeterminada($data['producto_id']);
$imagen = $img ? $img->foto : $data['foto'];
?>

<div class="col-sm-6 col-md-3 gridItem">
	<div class="thumbnail">
		<a title="<?php echo $data['nombre']; ?>" href="javascript:;" onclick="javascript:popUp(<?php echo $data['producto_id'] ?>)">
			<img alt="100%125" class="img-responsive" style="width: 100%;" src="<?php echo Yii::app()->baseUrl.'/../repository/'.$imagen; ?>" data-holder-rendered="true">
		</a>
		<div class="caption">
			<h3><a href="javascript:;" onclick="javascript:popUp(<?php echo $data['producto_id'] ?>)"><?php echo $data['nombre']; ?></a></h3>
		</div>
	</div>
	<?php if($data['nuevo'] == 1){ ?>
		<div class="nuevoItem">NUEVO</div>
	<?php } ?>
</div>
