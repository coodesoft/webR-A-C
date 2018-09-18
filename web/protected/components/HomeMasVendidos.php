<?php
class HomeMasVendidos extends CWidget {

    public $limit = 6;

    public function run() {

        $destacados = Productos::getDestacados($this->limit);

        $this->render('HomeMasVendidos',
            array(
                'destacados' => $destacados
            )
        );
    }

}