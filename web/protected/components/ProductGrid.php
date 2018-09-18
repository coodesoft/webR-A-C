<?php
class ProductGrid extends CWidget {
  public $dataProvider;
  public $categoria_id;
  public $pager = [];

  public function run() {
    $this->pager['pagesCount'] = ceil($this->dataProvider->totalItemCount/$this->dataProvider->pagination->pageSize);

    $Paginador = $this->widget('Pager',$this->pager,true);
    //mostramos paginador
    echo $Paginador;
    //mostramos elementos
    foreach ($this->dataProvider->getData() as $item){
      $this->widget('ProductCardMin',[
        'item'                => $item,
        'categoria'           => $item->categoria->categoria_id,
        'foto'                => $item->primeraFotoGaleria(),
        'isOferta'            => $item->isOferta,
        'stock'               => $item->stock,
        'etiqueta'            => $item->etiqueta,
        'cuotas_sobre_precio' => $item->cuotas_sobre_precio,
        'precio'              => $item->precio,
        'oferta'              => $item->oferta[0]->producto->precio,
      ]);
    }
    //mostramos paginador
    echo $Paginador;
  }
}
