<?php
class Pager extends CWidget {
  public $prevPageLabel;
  public $nextPageLabel;
  public $url;
  public $pagesCount;
  public $page;

  public function run() {
    $HTML =  '<div class="pagination clearfix"><ul id="yw0" class="yiiPager">';
    $HTML .= '<li class="first hidden"><a href="'.$this->url.'"></a></li>';

    $anterior  = $this->page-1;
    $siguiente = $this->page+1;

    if ($anterior > 0)
      $HTML .= '<li class="previous"><a href="'.$this->url.'&page='.$anterior.'">«</a></li>';

    for ($c=1;$c<=$this->pagesCount;$c++){
      if ($c == $this->page)
        $HTML .= '<li class="page selected"><a href="'.$this->url.'&page='.$c.'">'.$c.'</a></li>';
      else
        $HTML .= '<li class="page"><a href="'.$this->url.'&page='.$c.'">'.$c.'</a></li>';
    }

    if ($siguiente <= $this->pagesCount)
      $HTML .= '<li class="next"><a href="'.$this->url.'&page='.$siguiente.'">»</a></li>';

    $HTML .= '<li class="last"><a href="'.$this->url.'&page='.$this->pagesCount.'"></a></li>';
    $HTML .= '</ul></div>';
    echo $HTML;
  }
}
