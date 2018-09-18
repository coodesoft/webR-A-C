<?php
class HomeOfertas extends CWidget {

    public function run() {
        $promociones = Promociones::getPromociones();

        $arrPromo = [];
        foreach($promociones as $promocion) {
            $arrPromo[] = [
                'categoria_id' => $promocion->categoria_id,
                'producto_id'  => $promocion->producto_id
            ];
        }

        $ofertas = ProductosPrecios::getOfertas(null, null, $arrPromo);
        if (count($ofertas) || count($promociones))
          $this->render('HomeOfertas',
            [
                'promociones' => $promociones,
                'ofertas'     => $ofertas
            ]);
        else
          echo '';
    }

}
