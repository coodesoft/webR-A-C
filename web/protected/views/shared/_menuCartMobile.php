<div class="title">Item(s) agregado(s)</div>
<ul class="list">
    <?php
    $total = 0;
    foreach ($cartItems as $cartItem) {
        $link = Yii::app()->createAbsoluteUrl('/productos/detail/'.$cartItem->producto->categoria->categoria_id.'/'.$cartItem->producto->producto_id);
        $precio = $cartItem->producto->precio[ProductosPrecios::PRECIO_ONLINE_ID]['precio'];
        if ($cartItem->producto) {
            ?>
            <li class="item cart-item-row-mobile">
                <a href="<?php echo $link ?>" class="preview-image">
                    <img
                        class="preview"
                        src="<?php echo Commons::image('repository/'.$cartItem->producto->foto, 65, 65) ?>"
                        alt="">
                </a>
                <div class="description">
                    <a href="#"><?php echo $cartItem->producto->etiqueta ?></a>
                    <strong class="price"><?php echo $cartItem->cantidad ?> x $<?php echo Commons::formatPrice($precio) ?></strong>
                </div>
            </li>
            <?php
        }
    }
    ?>
</ul>
<div class="total">Total: <strong>$<?php echo Commons::formatPrice($cartItems['total']) ?></strong></div>
<a href="<?php echo Yii::app()->createAbsoluteUrl('/productos/checkout') ?>" class="btn btn-mega">Checkout</a>
<div class="view-link"><a href="<?php echo Yii::app()->createAbsoluteUrl('/productos/cart') ?>">Ver carrito</a></div>