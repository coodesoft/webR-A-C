<?php
class RightColumn extends CWidget {

    public function run() {
        $novedades = ProductosNovedades::model()->findAll();
        $aux = [];
		$prods = 0;
        foreach ($novedades as $key => $novedad) {
            $producto = Productos::getProductInfo($novedad->categoria_id, $novedad->producto_id);
		    if ($producto === null) {
                continue;
            }
            $novedad->producto = $producto;
            $aux [] = $novedad;
			$prods++;

    	    if ($prods == 15) {
    		  break;
    	   }
        }

        $this->render('RightColumn',
            [
                'novedades' => $aux,
            ]
        );
    }

}
