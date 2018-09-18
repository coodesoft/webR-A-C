<?php
class RelatedProducts extends CWidget {

    public $categoria_id;
    public $producto_id;
    public $limit;

    public function run() {

        //$productos = Productos::getProductosByCategoria($this->categoria_id, $this->limit, null, $this->producto_id);
        $productos = Productos::getRelatedProducts($this->categoria_id, $this->producto_id);

        $this->render('RelatedProducts',
            array(
                'categoria_id' => $this->categoria_id,
                'limit' => $this->limit,
                'productos' => $productos
            )
        );
    }

}