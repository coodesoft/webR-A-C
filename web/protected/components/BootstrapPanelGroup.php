<?php
class BootstrapPanelGroup extends CWidget {
  public $panels = [];
  public $gral   = ['class'=>'','id'=>'', 'panels'=>['class'=>'panel-default']];

  public function run() {
    $HTML = '<div class="panel-group '.$this->gral['class'].'" id="'.$this->gral['id'].'">';
    foreach ($this->panels as $l) {
      $HTML .= $this->getHtmlPanel($l);
    }
    $HTML .= '</div>';
    echo $HTML;
  }

  private function getHtmlPanel($l){
    $HTML = '<div class="panel '.$this->gral['panels']['class'].'">';
    //heading panel
    if (isset($l['active'])){
      $active = 'active';
    } else {
      $active = '';
    }

    $HTML .= '<div class="panel '.$this->gral['panels']['class'].'">
                <div class="panel-heading  '.$active.'">
                  <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#'.$l['id'].'">'.$l['title']['text'].'</a>
                  </h4>
               </div>
             </div>';
    //content panel
    $HTML .= $this->getHtmlPanelContent($l);
    //fin
    $HTML .= '</div>';
    return $HTML;
  }

  private function getHtmlPanelContent($p){
    if (isset($p['active'])){
      $auxClass = 'collapse in';
    } else {
      $auxClass = 'collapse';
    }

    $HTML = '<div id="'.$p['id'].'" class="panel-collapse '.$auxClass.'">
                <div class="panel-body"><div class="row"><div class="col-sm-12">';
    
    $HTML .= $p['content'];

    $HTML .= '   </div></div></div>
              </div>';
    return $HTML;
  }
}
