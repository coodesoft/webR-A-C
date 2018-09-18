<?php
class HtmlList extends CWidget {
  public $elements  = [];
  public $ul        = [];
  public $li        = [];

  public function run() {
    $HTML = '<ul class="'.$this->ul['class'].'">';
    foreach ($this->elements as $k => $r) {
      $HTML .= $this->getHtmlElement($r,$k);
    }
    $HTML .= '</ul>';
    echo $HTML;
  }

  private function getHtmlElement($r,$k){
    $HTML = '<li id="'.$k.'">';

    if(isset($r['enlacePromo'])){
      $HTML .= '<a href="'.$r['enlacePromo'].'" data-fancybox-type="iframe" class="fancybox fancyMp">';}
    else {
      $HTML .= '<a href="#">';
    }

    $HTML .= '<img width="80" src="'.Yii::app()->theme->baseUrl.$r['img'].'">';

    $HTML .= '</a>';

    $HTML .= '</li>';
    return $HTML;
  }
}
