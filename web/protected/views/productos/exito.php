<?php
$this->pageTitle = Yii::app()->name . ' :: ' . 'Venta procesada';
?>
<!-- Breadcrumbs -->
<section class="container">
    <nav class="breadcrumbs">
        <a href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">Home</a>
        <span class="divider">›</span> Venta procesada </nav>
</section>
<!-- //end Breadcrumbs -->

<section class="content-row">
    <div class="grey-container">
        <div class="container">
            <div class="col-lg-9">
                <div class="icon-circle-sm"><span class="icon icon-check"></span></div>
                <?php echo $message; ?>
            </div>
            <div class="col-lg-3"> <a class="btn btn-mega btn-lg" href="<?php echo Yii::app()->createAbsoluteUrl('/') ?>">IR A HOME</a></div>
        </div>
    </div>
</section>