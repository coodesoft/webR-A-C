<?php
if($cartItems->itemsCount == 0){ echo 'No tienes items en el carrito';} else {?>
Productos agregados
<ul class="list">
    <?php
    $total = 0;
    foreach ($cartItems as $cartItem) {
        //echo "<pre>"; var_dump($cartItem->producto->precio[15]['precio']); die;
        $link = Yii::app()->createAbsoluteUrl('/productos/detail/'.$cartItem->producto->categoria->categoria_id.'/'.$cartItem->producto->producto_id);
        $precio = $cartItem->precio_unitario;
        if ($cartItem->producto) {
            ?>
            <li class="item">
                <a
                    href="<?php echo $link ?>"
                    class="preview-image">
                    <img
                        class="preview"
                        src="<?php echo Commons::image('repository/'.$cartItem->producto->foto) ?>"
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
<div class="total">Total: <strong>$<?php
echo Commons::formatPrice($cartItems->total) ?></strong></div>
<a <?php if($cartItems->itemsCount == 0) echo 'disabled'; ?>
  href="<?php echo Yii::app()->createAbsoluteUrl('/productos/checkout') ?>" class="btn btn-mega">Comprar</a>
<div class="view-link"><a href="<?php echo Yii::app()->createAbsoluteUrl('/productos/cart') ?>">Ver carrito</a></div>
<?php } ?>
