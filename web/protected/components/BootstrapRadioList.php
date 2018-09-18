<?php
class BootstrapRadioList extends CWidget {
  public $radios  = [];
  public $radioGroup = '';

  public function run() {
    $HTML = ''; $ch = 1; $i = 1;
    foreach ($this->radios as $k => $r) {
      $HTML .= $this->getHtmlRadio($ch,$r,$k,$i);
      $ch = 0;
      $i++;
    }

    echo $HTML;
  }

  private function getHtmlRadio($ch,$r,$k,$i){
    $HTML = '<div class="radio">';
    $HTML .= '<label><input type="radio" checked="'.$ch.'" value="'.$k.'" id="'.$this->radioGroup.$i.'" name="'.$this->radioGroup.'">'.$r['name'].'</label>';

    if(isset($r['enlacePromo']))
      $HTML .= '<a href="'.$r['enlacePromo'].'" data-fancybox-type="iframe" class="fancybox fancyMp">(ver promos)</a>';

    if (isset($r['img']))
    $HTML .= '<div><img width="80" src="'.Yii::app()->theme->baseUrl.$r['img'].'"></div>';

    if(isset($r['checkout_notes']))
      $HTML .= '<p class="checkout_notes">'.$r['checkout_notes'].'</p>';

    $HTML .= '</div>';
    return $HTML;
  }
}
