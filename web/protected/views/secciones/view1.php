<?php
/* @var $this SeccionesController */
/* @var $model Secciones */


?>

<?php $this->widget('application.components.BreadCrumb', array(
  'crumbs' => array(
    array('name' => 'Inicio', 'url' => array('site/index')),
    array('name' => $model->titulo_seccion),
  ),
  'delimiter' => ' &rarr; ', // if you want to change it
  'bg' => 'bg_seccion_vacas.jpg', // if you want to change it
  'title' =>$model->titulo_seccion,
  'subtitle' =>$model->subtitulo_seccion
)); ?>




</div>
    <div class="section">
        <div class="wrapper">
            <div id="posts" style="float:left;">
                <div class="post-title">
                <h4><?php echo $model->titulo_seccion ?></h4>
                <div class="cline_blog"></div>
                </div>
                
                <div class="flexslider" style="overflow: hidden; padding: 25px 0;">
                    <ul class="slides" style="width: 800%; margin-left: -827.902px;">
                       <!--
 <li class="clone" style="width: 770px; float: left; display: block;"><a href="http://ryuka-design.com/Expose_Folio/2013/06/12/post-with-slider/"><img width="1200" height="375" alt="blog1" class="attachment-large-image" src="http://ryuka-design.com/Expose_Folio/wp-content/uploads/2013/06/blog1.jpg"></a></li>
-->
                        <li style="width: 770px; float: left; display: block;"><a href="#"><img width="1920" height="600" alt="office1" class="attachment-large-image" src="<?php echo Yii::app()->theme->baseUrl ?>/uploads/slide/img2.png" /></a></li>
                        <li style="width: 770px; float: left; display: block;"><a href="#"><img width="1200" height="375" alt="blog1" class="attachment-large-image" src="<?php echo Yii::app()->theme->baseUrl ?>/uploads/slide/img3.png"></a></li>
                        <li class="clone" style="width: 770px; float: left; display: block;"><a href="#"><img width="1920" height="600" alt="office1" class="attachment-large-image" src="<?php echo Yii::app()->theme->baseUrl ?>/uploads/slide/img1.png"></a></li>
                    </ul>
                    <ul class="flex-direction-nav">
                        <li><a href="#" class="prev">Previous</a></li>
                        <li><a href="#" class="next">Next</a></li>
                    </ul>
                </div>
                 
                    
                    <div class="entry">

                      <?php echo $model->texto_seccion ?>
            
                    </div>
                    
              
            </div>
            <div id="sidebar" style="float:right;">
                <?php $this->widget('application.components.Sidebar', array(
                    'ultimos_lotes_visible'=>1
                )); ?>
            </div>
            <div style="clear:both"></div>
      </div>
</div>

