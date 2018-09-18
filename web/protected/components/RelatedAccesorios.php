<?php
class RelatedAccesorios extends CWidget {

    public $categoria_id;
    public $producto_id;
    public $limit;

    public function run() {

        $accesorios = ProductosAccesorios::getRelatedAccesorios($this->categoria_id, $this->producto_id, $this->limit);

        $this->render('RelatedAccesorios',
            array(
                'categoria_id' => $this->categoria_id,
                'producto_id' => $this->producto_id,
                'limit' => $this->limit,
                'accesorios' => $accesorios
            )
        );
    }

}