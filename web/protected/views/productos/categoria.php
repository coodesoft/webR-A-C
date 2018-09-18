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

            <!-- Filters -->
            <div class="filters-panel">
                <div class="row">
                    <div class="col-sm-6 col-md-4 col-lg-4"> Ordenar
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
                    <div class="col-md-4 col-lg-4 hidden-sm hidden-xs">
                        <div class="view-mode"> Formato:&nbsp; <a href="#" class="view-grid"><span class="icon-th"></span></a> <a href="#" class="view-list"><span class="icon-th-list"></span></a> </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-4 hidden-xs">
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

            <!-- Listing products -->
            <div class="products-list products-list-small row">
                <?php
                $this->widget('zii.widgets.CListView', array(
                    'id' => 'productsGrid',
                    'dataProvider' => $dataProvider,
                    'itemView' => '_categoria',
                    'ajaxUpdate' => false,
                    'pagerCssClass'=>'pagination clearfix',
                    'template' => "{pager}\n{items}\n{pager}\n{summary}",
                    'summaryText'=>'<div class="double_line"></div>',
                    'pager' => array(
                        'cssFile' => Yii::app()->baseUrl . '/css/clistview.css',
                        'header' => false,
                        'firstPageLabel' => "",
                        'prevPageLabel' => '&laquo;',
                        'nextPageLabel' => '&raquo;',
                        'lastPageLabel' => "",
                    ),
                ));
                ?>
            </div>
        </section>
    </div>
</section>
<script>
    $(function(){
        $(document).on('click', '.perpage-select .dropdown-menu a', function(){
            var value = $(this).data('value');
            <?php
            extract($_GET);
            if ($keyword != '') {
                ?>
                location.href = '<?php echo Yii::app()->createAbsoluteUrl("/productos/search/".$id."?page=".$page."&keyword=".$_GET['keyword']."&order=".$order."&pageSize=") ?>'+value;
                <?php
            } else {
                ?>
                location.href = '<?php echo Yii::app()->createAbsoluteUrl("/productos/categoria/".$id."?page=".$page."&keyword=".$_GET['keyword']."&order=".$order."&pageSize=") ?>'+value;
                <?php
            }
            ?>
        });

        $(document).on('click', '.sort-select .dropdown-menu a', function(){
            var value = $(this).data('value');
            <?php
            extract($_GET);
            if ($keyword != '') {
            ?>
            location.href = '<?php echo Yii::app()->createAbsoluteUrl("/productos/search/".$id."?page=".$page."&keyword=".$_GET['keyword']."&pageSize=".$pageSize."&order=") ?>'+value;
            <?php
            } else {
            ?>
            location.href = '<?php echo Yii::app()->createAbsoluteUrl("/productos/categoria/".$id."?page=".$page."&keyword=".$_GET['keyword']."&pageSize=".$pageSize."&order=") ?>'+value;
            <?php
            }
            ?>
        });
    });
</script>