<?php
$this->pageTitle = Yii::app()->name . ' :: ' . $producto->etiqueta;
?>
<!-- Breadcrumbs -->
<section class="container">
    <nav class="breadcrumbs">
        <a href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">Home</a> <span class="divider">
            › &nbsp;&nbsp;
        <a href="<?php echo Yii::app()->createAbsoluteUrl('/productos/categoria/' . $categoria_id) ?>"> <?php echo $producto->categoria->etiqueta_web ?></a> <span class="divider">
            ›
        </span> <?php echo $producto->etiqueta ?> </nav>
</section>
<!-- //end Breadcrumbs -->

<section class="container">
<?php
$this->renderPartial(
    'quickview',
    array(
        'producto' => $producto,
        'categoria_id' => $categoria_id,
        'producto_id' => $producto_id,
        'center_cols' => $center_cols,
        'hide_ver_mas' => true
    )
)
?>

<?php
$this->widget(
    'RelatedProducts',
    array(
        'categoria_id' => $categoria_id,
        'limit' => 10,
        'producto_id' => $producto_id
    )
);
?>

<?php
$this->widget(
    'RelatedAccesorios',
    array(
        'categoria_id' => $categoria_id,
        'producto_id' => $producto_id,
        'limit' => 10
    )
);
?>
</section>

