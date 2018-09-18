<?php
/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 05/07/16
 * Time: 23:56
 */
//echo "<pre>"; var_dump($data->precio[ProductosPrecios::PRECIO_AHORRO_ID]); die;
$linkToDetail = Yii::app()->createAbsoluteUrl('/productos/detail/' . $data->categoria->categoria_id . '/' . $data->producto_id);
$linkToQuickView = Yii::app()->createAbsoluteUrl('/productos/quickview/' . $data->categoria->categoria_id . '/' . $data->producto_id);
?>
<div class="product-preview">
    <div class="preview animate scale"> <a href="<?php echo $linkToDetail ?>" class="preview-image">
        <img class="img-responsive animate scale"
             src="<?php echo Commons::image('repository/'.$data->foto, 270, 328); ?>"
             width="270" height="328" alt=""></a>
        <?php
        if ($data->promocion) {
            $this->widget('CountdownPromocion',
                array(
                    'extraClass' => '',
                    'promocion' => $data->promocion
                    )
            );
        }
        ?>
        <?php if ($data->isOferta === true) { ?>
            <ul class="product-controls-list right">
                <li><span class="label label-sale">AHORRO</span></li>
                <?php
                if ($data->promocion !== null) {
                    ?>
                    <li><span class="label">$<?php echo Commons::formatPrice($data->precio[ProductosPrecios::PRECIO_AHORRO_ID]['precio']) ?></span></li>
                    <?php
                } else {
                    ?>
                    <li><span class="label">$<?php echo Commons::formatPrice($data->oferta[0]->producto->precio[ProductosPrecios::PRECIO_AHORRO_ID]) ?></span></li>
                    <?php
                }
                ?>
            </ul>
        <?php } ?>
        <ul class="product-controls-list add-to-cart right hide-right">
            <li class="top-out-small"></li>
            <li>
                <?php echo CHtml::hiddenField('productLevel', $data->stock, array('data-categoria' => $data->categoria->categoria_id, 'class' => 'detail-input-cantidad', 'data-producto' => $data->producto_id)); ?>
                <a href="#" class="cart hidden" data-categoria="<?php echo $data->categoria->categoria_id ?>" data-producto="<?php echo $data->producto_id ?>"><span class="icon-basket"></span></a>
            </li>
        </ul>
        <a href="<?php echo $linkToQuickView ?>" class="quick-view fancybox fancybox.ajax hidden-xs"> Vista rápida </a> </div>

    <h3 class="title"><a href="<?php echo $linkToDetail ?>" class="preview-image"><?php echo $data->etiqueta ?></a></h3>
    <span class="price lista">Precio de lista: $<?php echo Commons::formatPrice($data->precio[ProductosPrecios::PRECIO_TARJETA_ID]['precio']) ?></span>
    <?php if ($data->isOferta) { ?>
        <?php
        if ($data->promocion !== null) {
            ?>
            <span class="price old">$<?php echo Commons::formatPrice($data->precio[ProductosPrecios::PRECIO_AUX_ID]['precio']) ?></span>
            <?php
        } else {
            ?>
            <span class="price old">$<?php echo Commons::formatPrice($data->oferta[0]->producto->precio[ProductosPrecios::PRECIO_AUX_ID]) ?></span>
            <?php
        }
        ?>
    <?php } ?>
    <span class="price new">$<?php echo Commons::formatPrice($data->precio[ProductosPrecios::PRECIO_ONLINE_ID]['precio']) ?></span>
    <?php if (isset($data->cuotas_sobre_precio)) { ?>
        <span class="price cuotas"><?php echo $data->cuotas_sobre_precio ?> x $<?php echo Commons::formatPrice($data->precio[ProductosPrecios::PRECIO_AUX_CUOTAS_SOBRE_PRECIO]->precio, 2) ?></span>
    <?php } ?>
    <!--rating-->
    <div class="list_rating hidden"><span class="rating"> <i class="icon-star-3"></i> <i class="icon-star-3"></i> <i class="icon-star-3"></i> <i class="icon-star-3"></i> <i class="icon-star-empty"></i> </span></div>
    <!--description-->
    <div class="list_description"><?php echo $data->descripcion ?></div>
    <!--buttons-->
    <div class="list_buttons hidden"> <a class="btn btn-mega pull-left" href="#">Comprar</a>
        <div class="add-to-links hidden">
            <ul>
                <li> <a  href="#"><i class="icon-heart"></i></a> <a  href="#">Add to Wish List</a> </li>
                <li> <a  href="#"><i class="icon-justice"></i></a> <a  href="#">Add to Compare</a> </li>
            </ul>
        </div>
    </div>
	<a href="#" class="cart btn btn-mega btn-md detail-add-to-cart" data-categoria="<?php echo $data->categoria->categoria_id ?>" data-producto="<?php echo $data->producto_id ?>">Comprar</a>
</div>

<?php
if (($index+1) % 5 == 0 && $index != 0) {
    ?>
    <div class="clearfix xs-hidden"></div>
    <?php
}
?>
