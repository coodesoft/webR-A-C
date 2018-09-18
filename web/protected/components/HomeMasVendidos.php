<?php
class HomeMasVendidos extends CWidget {

    public $limit = 6;

    public function run() {

        $destacados = Productos::getMasVendidos($this->limit);
        $this->render('HomeMasVendidos',
            [
                'destacados' => $destacados,
            ]
        );
    }

}
