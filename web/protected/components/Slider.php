<?php
class Slider extends CWidget {

    public function run() {

        $slides = Slides::getSlides(array(Slides::SLIDES_ALL, Slides::SLIDES_PRINCIPAL));

        $this->render('Slider', array(
            'slides' => $slides
        ));
    }

}