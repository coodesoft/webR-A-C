<div class="hombre-dama-barra">
	<div class="col-md-2 col-sm-2"></div>

	<?php
	if($selected==3 || $selected=='hombre'){
		$classHombre = 'producto-active';
		$classDama = 'producto-menu';
		$styleHombre = 'color:#000000; text-decoration:none;';
		$styleDama = 'color:#c8c8c8; text-decoration:none;';
	}else{
		$classHombre = 'producto-menu';
		$classDama = 'producto-active';
		$styleHombre = 'color:#c8c8c8; text-decoration:none;';
		$styleDama = 'color:#000000; text-decoration:none;';
	}
	?>

	<div class="col-md-12 col-sm-8">
	<a href="<?php echo Yii::app()->session['cliente'] ? Yii::app()->createAbsoluteUrl('/productos/index/hombre') : Yii::app()->createAbsoluteUrl('/productos/hombre') ?>" class="<?php echo $classHombre ?>" style="<?php echo $styleHombre ?>">CALZADO PARA HOMBRE</a>
	<a href="<?php echo Yii::app()->session['cliente'] ? Yii::app()->createAbsoluteUrl('/productos/index/mujer') : Yii::app()->createAbsoluteUrl('/productos/mujer') ?>" class="<?php echo $classDama ?>" style="<?php echo $styleDama ?>">CALZADO PARA DAMA</a>
	</div>

	<div class="col-md-2 col-sm-2"></div>
</div>