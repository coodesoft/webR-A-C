<?php
class ProductCardMin extends CWidget {
  public $item;
  public $isDataProvider      = false;
  public $categoria           = null;
  public $foto                = null;
  public $isOferta            = null;
  public $stock               = null;
  public $etiqueta            = null;
  public $cuotas_sobre_precio = null;
  public $precio              = null;
  public $oferta              = null;

  public $btnComprarEnabled   = true;
  public $debug = false;
  public function run() {
    if ($this->categoria === null)
      $this->categoria = $this->item->categoria_id;

    if (isset($this->item->producto)) {
      if ($this->foto === null)
        $this->foto = $this->item->producto->primeraFotoGaleria();

      if ($this->isOferta === null)
        $this->isOferta = $this->item->producto->isOferta;

      if ($this->stock === null)
        $this->stock = $this->item->producto->stock;

      if ($this->etiqueta === null)
        $this->etiqueta = $this->item->producto->etiqueta;

      if ($this->cuotas_sobre_precio === null)
        $this->cuotas_sobre_precio = $this->item->producto->cuotas_sobre_precio;

      if ($this->precio === null)
        $this->precio = $this->item->producto->precio;
    }

    echo $this->render('ProductCardMin',[
      'item'         => $this->item,
      'catPreDef'    => $this->isDataProvider,
      'categoria'    => $this->categoria,
      'foto'         => $this->foto,
      'isOferta'     => $this->isOferta,
      'stock'        => $this->stock,
      'etiqueta'     => $this->etiqueta,
      'cuotas'       => $this->cuotas_sobre_precio,
      'precio'       => $this->precio,
      'comprarBtn'   => $this->btnComprarEnabled,
      'urlDetail'    => Yii::app()->createAbsoluteUrl('/productos/detail/' .$this->categoria. '/' . $this->item->producto_id),
      'urlQuickView' => Yii::app()->createAbsoluteUrl('/productos/quickview/' .$this->categoria. '/' . $this->item->producto_id),
      'debug'        => $this->debug,
    ]);
  }
}
