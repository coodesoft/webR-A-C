<?php
class RightColumn extends CWidget {

    public function run() {

        $novedades = ProductosNovedades::model()->findAll();

		$prods = 0;
        foreach ($novedades as $key => &$novedad) {
            $producto = Productos::getProductInfo($novedad->categoria_id, $novedad->producto_id);
			if ($producto === null) {
                unset($novedades[$key]);
                continue;
            }
			$prods++;
            $novedad->producto = $producto;
			if ($prods == 15) {
				break;
			}
        }

        $this->render('RightColumn',
            array(
                'novedades' => $novedades
            )
        );
    }

}