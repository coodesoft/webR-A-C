<?php
class ProductList extends CWidget {
  public $titulo = '';
  public $items  = [];
  public $costoEnvio  = 0;
  public $costoEnvioRapido = 0;

  public function run() {
    if ($this->items->itemsCount == 0) return true;
    //limpiamos el arreglo y haciendo otro nuevo en el cual poder pasar como parametro
    //codigo totalmente temporal
    $ArrayItems = [];
    $c = 0;
    foreach ($this->items as $item) {
      if ($item->producto) {
        $ArrayItems[$c][0] = '<a href="#"
           class="remove-button visible-xs"
           data-categoria="'.$item->producto->categoria->categoria_id.'"
           data-producto="'.$item->producto->producto_id.'">
            <span class="icon-cancel-2 "></span>
        </a>
        <a href="#">
            <img class="preview"
                 src="'.Commons::image('repository/' . $item->producto->foto, 52, 52).'">
        </a>';

        $ArrayItems[$c][1] = '<span class="td-name visible-xs">Producto</span><a
            href="#">'.$item->producto->etiqueta.'</a>';

        $ArrayItems[$c][2] = '<span class="td-name visible-xs">Cantidad</span>
            <div class="input-group quantity-control cart-quantity-control">
                <a href="#">'.$item->cantidad.'</a>
            </div>';

        $ArrayItems[$c][3] = '<span class="td-name visible-xs">Unitario</span>
        $<span class="cart-item-unitario">'.Commons::formatPrice($item->producto->precio[ProductosPrecios::$PRECIO_TRANSFERENCIA_ID]['precio']).'</span>'.
        '<input type="hidden" class="cart-item-unitario-mercadopago" value="'.Commons::formatPrice($item->producto->precio[ProductosPrecios::$PRECIO_MERCADOPAGO_ID]['precio'],0,'').'"/>'.
        '<input type="hidden" class="cart-item-unitario-todopago" value="'.Commons::formatPrice($item->producto->precio[ProductosPrecios::$PRECIO_TODOPAGO_ID]['precio'],0,'').'"/>'.
        '<input type="hidden" class="cart-item-unitario-transferencia" value="'.Commons::formatPrice($item->producto->precio[ProductosPrecios::$PRECIO_TRANSFERENCIA_ID]['precio'],0,'').'"/>';

        $ArrayItems[$c][4] = '<span class="td-name visible-xs">Total</span>
        $<span class="cart-item-total">'.Commons::formatPrice(Commons::formatPrice($item->producto->precio[ProductosPrecios::$PRECIO_TRANSFERENCIA_ID]['precio'],0,'') * $item->cantidad).'</span>';

        $total += Commons::formatPrice($item->producto->precio[ProductosPrecios::$PRECIO_TRANSFERENCIA_ID]['precio'],0,'') * $item->cantidad;
        $c ++;
      }
    }

    $HTML = '<div class="shopping_cart">
        <div class="box">
            <div class="shopping_cart_detail"><h3>'.$this->titulo.'</h3>';

    $HTML .= $this->widget('HTMLTable',[
      'columns' => ['','Producto','Cantidad','Unitario','Total'],
      'data'    => $ArrayItems,
      'tr'      => ['class' =>'cart-item-row'],
    ], true);
    //se agrega informacion de costo de envio y de total
    if ($this->costoEnvio == 0)
      $envio = 'Gratis';
    else {
      $total += $this->costoEnvio;
      $envio  = '$'.Commons::formatPrice($this->costoEnvio);
    }
    $HTML .= '<div> <b class="title">Costo de envío:</b> <span id="costo_envio_text">'.$envio .'</span>
    <input id="costo_envio" type="hidden" name="costo_envio" value="'.$this->costoEnvio.'" />
    <input id="costo_envio_rapido" type="hidden" name="costo_envio_rapido" value="'.$this->costoEnvioRapido.'" />
     </div>';

    $HTML .= '<div>
        <p><b class="title">Total:</b> <span class="price">$<span class="cartTotal">'.Commons::formatPrice($total).'</span></span></p>
    </div>';

    $HTML .= '</div><div class="clearfix"><a href="'.Yii::app()->createAbsoluteUrl('productos/cart').'">Modificar carrito</a></div></div></div>';
    echo $HTML;
  }
}
