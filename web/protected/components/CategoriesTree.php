<?php
class CategoriesTree extends CWidget {

    public function run() {

        $categorias = Commons::getCategoriasForTree();
        $menu = Commons::buildTree($categorias, array(), 0, false, 2);

        $this->render('CategoriesTree',
            array(
                'menu' => $menu
            )
        );
    }

}