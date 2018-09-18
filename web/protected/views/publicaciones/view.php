<?php
$this->pageTitle = Yii::app()->name . ' :: ' . $model->titulo;
?>
<!-- Breadcrumbs -->
<section class="container">
    <nav class="breadcrumbs"> <a href="<?php echo Yii::app()->createAbsoluteUrl('/'); ?>">Home</a> <span class="divider">›</span> Publicaciones </nav>
</section>
<!-- //end Breadcrumbs -->

<!-- Two column content -->
<section class="container">
    <div class="row">
        <section class="col-sm-8 col-md-8 col-lg-9">

            <!-- Blog post -->
            <div class="blog-post container-paper">
                <div class="title">
                    <h2><a href="#"><?php echo $model->titulo ?></a></h2>
                </div>
                <div class="post-container">
                    <?php
                    $image = $model->imagen_destacada != '' && file_exists(Yii::app()->basePath . '/../../repository/'.$model->imagen_destacada)
                        ? Commons::image('repository/' . $model->imagen_destacada, 831, 288)
                        : Yii::app()->theme->baseUrl . '/images/temp/block-image-04-01-831x288.jpg';
                    ?>
                    <img
                        class="img-responsive animate scale"
                        src="<?php echo $image ?>"
                        alt="">
                    <div class="row">
                        <div class="text">
                            <?php echo $model->bajada; ?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="text">
                            <?php echo $model->texto; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- //end Blog post -->
            <div class="post-navigation hidden">
                <div class="pull-left"><a href="#" class="btn btn-mega btn-inverse"> <span class="icon icon-arrow-left-6"></span> &nbsp; Prev POST</a></div>
                <div class="pull-right"><a href="#" class="btn btn-mega btn-inverse"> Next Post &nbsp;<span class="icon icon-arrow-right-5"></span> </a></div>
            </div>
            <div class="clearfix"></div>
        </section>
    </div>
</section>
<!-- //end Two columns content -->