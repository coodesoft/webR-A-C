<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
    'Error',
);
?>

<!-- Error page -->
<section class="container error-page">
    <div class="error-number">
        <?php echo $code; ?>
        <div class="icon"><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/large-icon-tea.png" alt=""></div>
    </div>
    <h2>Lo sentimos!</h2>
    <h5>La página que intentas visitar, no existe. Por favor:</h5>
    <ul class="list">
        <li>Asegúrate de escribir la URL correctamente.</li>
        <li>Si has hecho click en algún enlace para llegar aquí, el mismo está desactualizado.</li>
    </ul>
</section>
<!-- //end Error page -->