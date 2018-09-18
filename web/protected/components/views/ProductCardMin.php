<div class="item">
    <div class="product-preview">
        <div class="preview animate scale">
            <a href="<?= $urlDetail ?>" class="preview-image">
                <img
                    class="img-responsive animate scale"
                    src="<?php echo Commons::image('repository/'.$foto, 270, 328); ?>"
                    width="270" height="328" alt="">
            </a>
            <?php if ($countdown === false) {
                $this->widget('CountdownPromocion',
                    [
                        'extraClass' => '',
                        'promocion'  => $item->promocion
                    ]
                );
            } ?>
            <?php if ($nuevo === true) { ?>
                <ul class="product-controls-list">
                    <li><span class="label label-new">NUEVO</span></li>
                </ul>
            <?php } ?>

            <?php if($isOferta && $precio[ProductosPrecios::PRECIO_AHORRO_ID]['precio'] > 0){ ?>
                <ul class="product-controls-list right">
                    <li><span class="label label-sale">AHORRO</span></li>
                    <li><span class="label">$<?php echo Commons::formatPrice($precio[ProductosPrecios::PRECIO_AHORRO_ID]['precio'],2) ?></span></li>
                </ul>
            <?php } ?>
            <ul class="product-controls-list right hide-right">
                <li class="top-out"></li>
                <?php echo CHtml::hiddenField('productLevel', $stock, ['data-categoria' => $categoria, 'class' => 'detail-input-cantidad', 'data-producto' => $item->producto_id]); ?>
                <li class="hidden"><a href="#" class="cart" data-categoria="<?= $categoria ?>" data-producto="<?= $item->producto_id ?>"><span class="icon-basket"></span></a></li>
            </ul>
            <a
                href="<?= $urlQuickView ?>"
                class="quick-view fancybox fancybox.ajax hidden-xs">
                <span class="rating hidden">
                    <i class="icon-star-3"></i>
                    <i class="icon-star-3"></i>
                    <i class="icon-star-3"></i>
                    <i class="icon-star-3"></i>
                    <i class="icon-star-empty"></i>
                </span>
                <span class="icon-zoom-in-2"></span> Vista rápida
            </a>
            <div class="quick-view visible-xs hidden">
                <span class="rating">
                    <i class="icon-star-3"></i>
                    <i class="icon-star-3"></i>
                    <i class="icon-star-3"></i>
                    <i class="icon-star-3"></i>
                    <i class="icon-star-empty"></i>
                </span>
            </div>
        </div>
        <h3 class="title">
            <a href="<?= $urlDetail ?>">
                <?= $etiqueta ?>
            </a>
        </h3>
        <?php
          if (isset($precio[ProductosPrecios::$PRECIO_LISTA_ID]['precio']) && ProductosPrecios::$PRECIO_LISTA_ID != Precios::NINGUNO && $precio[ProductosPrecios::$PRECIO_LISTA_ID]['precio']>0)
            echo '<span class="price lista">Precio de lista: $'.Commons::formatPrice($precio[ProductosPrecios::$PRECIO_LISTA_ID]['precio']).'</span>';

          if (isset($precio[ProductosPrecios::$PRECIO_ONLINE_ID]['precio']) && ProductosPrecios::$PRECIO_ONLINE_ID != Precios::NINGUNO && $precio[ProductosPrecios::$PRECIO_ONLINE_ID]['precio']>0)
            echo '<span class="price new">$'.Commons::formatPrice($precio[ProductosPrecios::$PRECIO_ONLINE_ID]['precio']).'</span>';

          if (isset($precio[ProductosPrecios::$PRECIO_TARJETA_ID]['precio']) && $cuotas > 1 && ProductosPrecios::$PRECIO_TARJETA_ID != Precios::NINGUNO && $precio[ProductosPrecios::$PRECIO_TARJETA_ID]['precio']>0)
            echo '<span class="price cuotas">'.$cuotas.' x $'.Commons::formatPrice($precio[ProductosPrecios::$PRECIO_TARJETA_ID]['precio']/$cuotas,2).'</span>';

          if ($comprarBtn){
            if ($stock <= 0)
                $disabled= 'disabled="disabled"';
            echo '<a '.$disabled.' href="#" class="cart btn btn-mega btn-md detail-add-to-cart" data-categoria="'.$categoria.'" data-producto="'.$item->producto_id.'">Comprar</a>';
          }
        ?>
    </div>
</div>
