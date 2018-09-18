<?php
class HomeSliderRight extends CWidget {

    public $extraCss; // services-block hidden-xs noBorders
    public $tipos; // array(Slides::SLIDES_ALL, Slides::SLIDES_SECUNDARIO)
    public $width;
    public $height;
    public $zc;
    public $mini;

    public function run() {

        $slides = Slides::getSlides($this->tipos);

        $this->render('HomeSliderRight', array(
            'slides' => $slides,
            'extraCss' => $this->extraCss,
            'width' => isset($this->width) ? $this->width : 1170,
            'height' => isset($this->height) ? $this->height : 208,
            'zc' => isset($this->zc) ? $this->zc : 1,
            'mini' => $this->mini
        ));
    }

}