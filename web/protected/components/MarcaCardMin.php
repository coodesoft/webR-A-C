<?php
class MarcaCardMin extends CWidget {
  public $marca;
  public $isDataProvider      = false;
  public $imagen                = null;
  public $etiqueta            = null;
  public $url = null;

  public $btnComprarEnabled   = true;
  public $debug = false;
  public function run() {
  
    if ($this->imagen === null)
      $this->imagen = $this->marca->logo;

    if ($this->etiqueta === null)
      $this->etiqueta = $this->marca->nombre;

    echo $this->render('MarcaCardMin',[
      'model'         => $this->marca,
      'imagen'         => $this->imagen,
      'etiqueta'     => $this->etiqueta,
      'urlDetail'    => $this->url .'marca=' . $this->marca->producto_marca_id,
      'debug'        => $this->debug,
    ]);
  }
}
