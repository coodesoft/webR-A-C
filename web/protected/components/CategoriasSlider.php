<?php
class CategoriasSlider extends CWidget {

    public function run() {

        $slides = Slides::getSlides(array(Slides::SLIDES_ALL, Slides::SLIDES_CATEGORIA));

        $this->render('CategoriasSlider', array(
            'slides' => $slides
        ));
    }

}