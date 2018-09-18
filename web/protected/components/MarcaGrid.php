<?php
class MarcaGrid extends CWidget {
  public $marcas;
  public $categoria_id;
  public $pager = [];
  public $url;

  public function run() {

    //mostramos elementos
    foreach ($this->marcas as $marca){
      $this->widget('MarcaCardMin',[
        'marca'                => $marca,
        'etiqueta'            => $marca->nombre,
        'imagen'            => $marca->logo,
        'url' => $this->url
      ]);
    }

  }
}
