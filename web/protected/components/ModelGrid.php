<?php
class ModelGrid extends CWidget {
  public $modelos;
  public $categoria_id;
  public $pager = [];
  public $url;

  public function run() {

    //mostramos elementos
    foreach ($this->modelos as $model){
      $this->widget('ModelCardMin',[
        'model'                => $model,
        'etiqueta'            => $model->nombre,
        'imagen'            => $model->imagen,
        'url' => $this->url
      ]);
    }

  }
}
