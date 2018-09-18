<?php
class ModelCardMin extends CWidget {
  public $model;
  public $isDataProvider      = false;
  public $imagen                = null;
  public $etiqueta            = null;
  public $url = null;

  public $btnComprarEnabled   = true;
  public $debug = false;
  public function run() {
  
    if ($this->imagen === null)
      $this->imagen = $this->model->imagen;

    if ($this->etiqueta === null)
      $this->etiqueta = $this->model->nombre;

    echo $this->render('ModelCardMin',[
      'model'         => $this->model,
      'catPreDef'    => $this->isDataProvider,
      'imagen'         => $this->imagen,
      'logo'         => $this->model->marca->logo,
      'etiqueta'     => $this->etiqueta,
      'urlDetail'    => $this->url .'&model=' . $this->model->producto_modelo_id,
      'debug'        => $this->debug,
    ]);
  }
}
