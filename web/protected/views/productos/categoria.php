<?php
/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 05/07/16
 * Time: 23:51
 */
//echo "<pre>"; var_dump($dataProvider->rawData[0]->categoria->padre); die;
$this->pageTitle = Yii::app()->name . ' :: ' . $dataProvider->rawData[0]->categoria->etiqueta_web
?>
<!-- Two columns content -->
<section class="container content">
    <div class="row">
        <?php //$this->widget('LeftColumn') ?>

        <section class="col-lg-12">
            <!-- slider cuerpo -->
            <?php
            $this->widget('CategoriasSlider');
            ?>

            <?php 
            extract($_GET);
            $url = '/productos/';

            if (isset($keyword) && $keyword != null && $keyword != '') 
                $url .= "search/";
            else
                $url .= "categoria/$id";

            if (isset($order) && $order != null)
                $url.= "?order=".$order;

            if (isset($pageSize) && $pageSize != null)
                $url.= "&pageSize=".$pageSize;

            if (isset($keyword) && $keyword != null && $keyword != '') 
                $url.= "&keyword=".$keyword;

            if (isset($marca) && $marca != null)
                $url.= "&marca=".$marca;

            if (isset($model) && $model != null)
                $url.= "&model=".$model;
            ?> 
            <!-- Listing marcas -->
            <?php if (sizeof($marcas) > 0) { ?>
            <h3>MARCAS</h3>
            <div class="products-list products-marcas products-list-small row">
              <?php
                $this->widget('MarcaGrid',[
                  'marcas' => $marcas,
                  'url'           => Yii::app()->createAbsoluteUrl($url.'&'),
               /*   'pager'        => [
                    'prevPageLabel' => '&laquo;',
                    'nextPageLabel' => '&raquo;',
                    'page'          => $page,
                    
                  ],*/
                ]);
                ?>
            </div>
            <?php } ?>

            <!-- Listing models -->
            <?php if (sizeof($modelos) > 0) { ?>
            <h3>MODELOS</h3>
            <div class="products-list products-models products-list-small row">
              <?php
                $this->widget('ModelGrid',[
                  'modelos' => $modelos,
                  'url'           => Yii::app()->createAbsoluteUrl($url),
               /*   'pager'        => [
                    'prevPageLabel' => '&laquo;',
                    'nextPageLabel' => '&raquo;',
                    'page'          => $page,
                    
                  ],*/
                ]);
                ?>
            </div>
             <?php } ?>

             <?php if (sizeof($dataProvider->getData()) > 0) { ?>
            <!-- Filters -->
            <div class="filters-panel">
                <div class="row">
                    <div class="col-sm-6 col-md-3 col-lg-3"> Ordenar
                        <div class="btn-group btn-select sort-select">
                            <a href="#" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                <span class="value"><?php echo $labelOrder ?></span> <span class="caret min"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="#" data-value="<?php echo Productos::VALUE_ORDER_PRICE_LOW ?>"><?php echo Productos::LABEL_ORDER_PRICE_LOW ?></a></li>
                                <li><a href="#" data-value="<?php echo Productos::VALUE_ORDER_PRICE_HIGH ?>"><?php echo Productos::LABEL_ORDER_PRICE_HIGH ?></a></li>
                                <li><a href="#" data-value="<?php echo Productos::VALUE_ORDER_NAME_ASC ?>"><?php echo Productos::LABEL_ORDER_NAME_ASC ?></a></li>
                                <li><a href="#" data-value="<?php echo Productos::VALUE_ORDER_NAME_DESC ?>"><?php echo Productos::LABEL_ORDER_NAME_DESC ?></a></li>
                                <li><a href="#" data-value="<?php echo Productos::VALUE_ORDER_LAST_CREATED ?>"><?php echo Productos::LABEL_ORDER_LAST_CREATED ?></a></li>
                                <li><a href="#" data-value="<?php echo Productos::VALUE_ORDER_AHORRO_DESC ?>"><?php echo Productos::LABEL_ORDER_AHORRO_DESC ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 hidden-sm hidden-xs">
                        <div class="view-mode"> Formato:&nbsp; <a href="#" class="view-grid"><span class="icon-th"></span></a> <a href="#" class="view-list"><span class="icon-th-list"></span></a> </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-3 hidden-xs">
                        <div class="pull-right"> Mostrar
                            <div class="btn-group btn-select perpage-select">
                                <a href="#" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                    <span class="value"><?php echo $pageSize ?></span> <span class="caret min"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="#" data-value="15">15</a></li>
                                    <li><a href="#" data-value="30">30</a></li>
                                    <li><a href="#" data-value="50">50</a></li>
                                    <li><a href="#" data-value="100">100</a></li>
                                </ul>
                            </div>
                            por página </div>
                    </div>
                </div>
                <div class="divider"></div>
            </div>
            <!-- //end Filters -->
            <?php } ?>

            <!-- Listing products -->
            <div class="products-list products-list-small row">
              <?php

                $this->widget('ProductGrid',[
                  'dataProvider' => $dataProvider,
                  'pager'        => [
                    'prevPageLabel' => '&laquo;',
                    'nextPageLabel' => '&raquo;',
                    'page'          => $page,
                    'url'           => Yii::app()->createAbsoluteUrl($url),
                  ],
                ]);
                ?>
            </div>
        </section>
    </div>
</section>
<script>

    $(function(){

         <?php
        extract($_GET);

        $url = '/productos/';

        if (isset($_GET['keyword']) && $_GET['keyword'] != null && $_GET['keyword'] != '') 
            $url .= "search/";
        else
            $url .= "categoria/$id";

        $url.= "?page=1";

        if (isset($_GET['keyword']) && $_GET['keyword'] != null && $_GET['keyword'] != '') 
            $url.= "&keyword=".$_GET['keyword'];

        if (isset($marca) && $marca != null)
            $url.= "&marca=".$marca;

        if (isset($model) && $model != null)
            $url.= "&model=".$model
        ?>

        $(document).on('click', '.perpage-select .dropdown-menu a', function(){
            var value = $(this).data('value');
            
            url = '<?php echo Yii::app()->createAbsoluteUrl($url."&order=$order&pageSize=") ?>'+value;

            location.href = url;
             
        });

        $(document).on('click', '.sort-select .dropdown-menu a', function(){
            var value = $(this).data('value');
            
            url = '<?php echo Yii::app()->createAbsoluteUrl($url."&pageSize=$pageSize&order=") ?>'+value;

            location.href = url;

        });
    });
</script>
