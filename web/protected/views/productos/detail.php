<?php
$this->pageTitle = Yii::app()->name . ' :: ' . $producto->etiqueta;
?>
<!-- Breadcrumbs -->
<section class="container">
    <nav class="breadcrumbs">
        <a href="<?= Yii::app()->createAbsoluteUrl('/') ?>">Home</a> <span class="divider">
            › &nbsp;&nbsp;
        <a href="<?= Yii::app()->createAbsoluteUrl('/productos/categoria/' . $categoria_id) ?>"> <?= $producto->categoria->etiqueta_web ?></a> <span class="divider">
            ›
        </span> <?= $producto->etiqueta ?> </nav>
</section>
<!-- //end Breadcrumbs -->

<section class="container">
    <div class="product-view">
        <?php
        $this->renderPartial(
            'quickview',
            [
                'producto'     => $producto,
                'categoria_id' => $categoria_id,
                'producto_id'  => $producto_id,
                'center_cols'  => $center_cols,
                'hide_ver_mas' => true
            ]
        )
        ?>
    </div>

<?php
$this->widget(
    'RelatedProducts',
    [
        'categoria_id' => $categoria_id,
        'limit'        => 10,
        'producto_id'  => $producto_id
    ]
);
?>

<?php
$this->widget(
    'RelatedAccesorios',
    [
        'categoria_id' => $categoria_id,
        'producto_id'  => $producto_id,
        'limit'        => 10
    ]);
?>
</section>
