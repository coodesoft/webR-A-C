<?php
class HomePublicaciones extends CWidget {

    public $limit = 15;

    public function run() {

        $publicaciones = Publicaciones::getPublicacionesHome($this->limit);

        $this->render('HomePublicaciones',
            array(
                'publicaciones' => $publicaciones
            )
        );
    }

}