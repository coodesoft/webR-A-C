<?php
class HomeDestacados extends CWidget {

    public $limit = 6;

    public function run() {

        $destacados = Productos::getDestacados($this->limit);
        $this->render('HomeDestacados',
            [
                'destacados' => $destacados,
            ]
        );
    }

}
