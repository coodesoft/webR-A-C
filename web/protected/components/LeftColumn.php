<?php
class LeftColumn extends CWidget {

    public $limit = 6;

    public function run() {

        $destacados = Productos::getDestacados($this->limit);
        $publicaciones = Publicaciones::getPublicacionesHome($this->limit);

        $this->render('LeftColumn',
            array(
                'destacados' => $destacados,
                'publicaciones' => $publicaciones
            )
        );
    }

}