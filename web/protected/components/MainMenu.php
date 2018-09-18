<?php
class MainMenu extends CWidget {

    public function run() {

        $opciones = ConfiguracionesWeb::getConfigWeb();
        $keys = explode(',', $opciones->menu_keys);

        $arrNivel0 = [];
        foreach ($keys as $key) {
            list($actual, $padre) = explode('_', $key);
            if ($padre == 0) {
                $categoria = Categorias::model()->findByPk($actual);
                array_push($arrNivel0, $categoria->attributes);
            }
        }

        foreach ($arrNivel0 as &$item) {
            $item['hijos'] = [];
            foreach ($keys as $key) {
                list($actual, $padre) = explode('_', $key);
                if ($item['categoria_id'] == $padre) {
                    $categoria = Categorias::model()->findByPk($actual);
                    $item['hijos'][] = $categoria->attributes;
                }
            }
        }

        foreach ($arrNivel0 as &$item) {
            if (count($item['hijos'])) {
                foreach ($item['hijos'] as &$n2) {
                    $n2['hijos'] = [];
                    foreach ($keys as $key) {
                        list($actual, $padre) = explode('_', $key);
                        if ($n2['categoria_id'] == $padre) {
                            $categoria = Categorias::model()->findByPk($actual);
                            $n2['hijos'][] = $categoria->attributes;
                        }
                    }
                }
            }
        }

        $cartItems = CarritoProductosWeb::getCartItems();

        $this->render('MainMenu', array(
            'nivel0' => $arrNivel0,
            'comparador_enabled' => $opciones->comparador_enabled,
            'cartItems' => $cartItems
        ));
    }

}