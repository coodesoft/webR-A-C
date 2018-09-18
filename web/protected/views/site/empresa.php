<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' :: Nuestra Empresa';

?>
<div class="title">Nuestra Empresa</div>


<div class="body-wrapper">
<div class="container">
<div class="row">

<div class="col-md-6 col-sm-6">
<div class="cycle-slideshow frame1"
data-cycle-slides="> .slider-img"
data-cycle-swipe="true"
data-cycle-prev=".cycle-prev"
data-cycle-next=".cycle-next"
data-cycle-overlay-fx-out="slideUp"
data-cycle-overlay-fx-in="slideDown"
data-cycle-timeout=0>


<!-- slider item -->
<div class="slider-img">
<img src="<?php echo Yii::app()->theme->baseUrl ?>/images/empresa01.jpg" class="img-responsive"/>
</div>
<!-- //slider item// -->
</div>
</div>               

<div class="col-md-6 col-sm-6">
<div class="title-block clearfix">
<h3 class="h3-body-title">Original desde 1971</h3>
</div>
<p class="texto-empresa">
Sorba es sinónimo de calidad  desde 1971.
Con la visión y el compromiso de sus orígenes, su fundador continúa, como hace más de 45 años, en el detalle de cada nueva colección.
Evolucionando con las tendencias de la moda internacional, pero con una impronta personal centrada en la funcionalidad, las colecciones de SORBA y CANDILEJAS captan (y reflejan) las necesidades de sus consumidores.
Estilo, confort, calidad y moda, los diferenciales del calzado de LOAFERS y sus marcas.
</p>

</div>


</div>
</div>
</div>

<div class="barra-rosa"><img src="<?php echo Yii::app()->theme->baseUrl ?>/images/zapato-barra-rosa.jpg"></div>


<div class="body-wrapper">
<div class="container">
<div class="row">


<div class="col-md-6 col-sm-6" style="float:right;">
<div class="cycle-slideshow frame1"
data-cycle-slides="> .slider-img"
data-cycle-swipe="true"
data-cycle-prev=".cycle-prev"
data-cycle-next=".cycle-next"
data-cycle-overlay-fx-out="slideUp"
data-cycle-overlay-fx-in="slideDown"
data-cycle-timeout=0>



<!-- slider item -->
<div class="slider-img">
<img src="<?php echo Yii::app()->theme->baseUrl ?>/images/empresa02.jpg" class="img-responsive"/>
</div>
<!-- //slider item// -->
</div>
</div>               

<div class="col-md-6 col-sm-6">
<div class="title-block clearfix">
<h3 class="h3-body-title">Manufactura</h3>
</div>
<p class="texto-empresa">
Cada zapato de LOAFERS es una pieza pensada como única, como único es cada cliente.
Dedicación y experiencia técnica, desde los bocetos en papel hasta su
manufactura final.
Y junto a la incorporación contínua de tecnología, las manos de nuestros artesanos, como etapa irreemplazable en el proceso integral.
Esta visión, sumada al empleo de los mejores materiales, conforman un producto diferencial reconocido por el mercado desde 1971.
</p>

</div>

</div>
</div>


</div>

<?php
$this->renderPartial('_accesoClientes');
?>