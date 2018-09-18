<?php
class HomeOfertas extends CWidget {

    public function run() {

        $promociones = Promociones::getPromociones();

        $arrPromo = array();
        foreach($promociones as $promocion) {
            $arrPromo[] = array(
                'categoria_id' => $promocion->categoria_id,
                'producto_id' => $promocion->producto_id
            );
        }

        $ofertas = ProductosPrecios::getOfertas(null, null, $arrPromo);

        $this->render('HomeOfertas',
            array(
                'promociones' => $promociones,
                'ofertas' => $ofertas
            )
        );
    }

}