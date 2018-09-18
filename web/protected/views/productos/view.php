<?php
/* @var $this ProductosController */
/* @var $model Productos */

$this->breadcrumbs=array(
	'Productoses'=>array('index'),
	$model->producto_id,
);

$this->menu=array(
	array('label'=>'List Productos', 'url'=>array('index')),
	array('label'=>'Create Productos', 'url'=>array('create')),
	array('label'=>'Update Productos', 'url'=>array('update', 'id'=>$model->producto_id)),
	array('label'=>'Delete Productos', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->producto_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Productos', 'url'=>array('admin')),
);
?>

<h1>View Productos #<?php echo $model->producto_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'producto_id',
		'nombre',
		'descripcion',
		'foto',
		'activo',
	),
)); ?>
