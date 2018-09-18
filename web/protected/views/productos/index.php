<?php
/* @var $this ProductosController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = $categoria->nombre . ' :: Productos :: ' . Yii::app()->name;
?>

<!-- Master Wrap : starts -->

<section class="mastwrap page-top-space">


    <div class="container-fluid">
        <div class="row">
            <article class="col-md-12 text-center works-bg page-bg parallax">
                <h1 class="main-heading font2 white"><span><?php echo $categoria->nombre ?></span></h1>
            </article>
        </div>
    </div>

    <div class="container">

        <div class="separator">&nbsp;</div>

        <div class="row add-bottom">

            <div id="works-container" class="works-container white-bg container clearfix">
            <?php
            foreach ($productos as $producto) {
                $fotos = $producto->fotos;
                if(count($fotos)){
                    ?>
                    <!-- start : works-item -->
                    <div class="works-item works-item-one-third zoom branding web">
                        <img alt="" title="" class="img-responsive" src="<?php echo Yii::app()->baseUrl.'/../repository/'.$fotos[0]->foto; ?>"/>
                        <a class="" rel="prettyPhoto[kp-mixed-<?php echo $producto->producto_id; ?>]" data-gall="portfolio-gallery" href="<?php echo Yii::app()->baseUrl.'/../repository/'.$fotos[0]->foto; ?>">
                            <div class="works-item-inner valign">
                                <h3 class="dark"><?php echo $producto->nombre ?></h3>
                                <p class="dark"><span class="dark"><?php echo $categoria->nombre ?></span></p>
                            </div>
                        </a>
                        <?php
                        if(count($fotos)>1) {
                            for ($i=1; $i < count($fotos); $i++) {
                                ?>
                                <a class="hidden" rel="prettyPhoto[kp-mixed-<?php echo $producto->producto_id; ?>]" data-gall="portfolio-gallery" href="<?php echo Yii::app()->baseUrl.'/../repository/'.$fotos[$i]->foto; ?>"></a>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <!-- end : works-item -->
                    <?php
                }
            }
            ?>
          </div>
          <!-- end : works-container -->
        </div>
    </div>

    <?php echo $this->renderPartial('/shared/workWithUs', array('colorClass' => 'offwhite-bg', 'separatorImg' => '02-color.png', 'btnExposeClass' => 'btn-expose-dark')); ?>

</section>
<!-- Master Wrap : ends -->

<script type="text/javascript">
$(function(){
	$("a[rel^='prettyPhoto']").prettyPhoto({
        show_title: true,
        slideshow:false,
        hideflash: true
    });
});
</script>