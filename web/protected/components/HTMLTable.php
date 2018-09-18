<?php
class HTMLTable extends CWidget{
  private $header = '';

  public $body    = '';

  public $columns = [];
  public $data    = [];

  public $tr      = ['class'=>''];
  public $td      = ['class'=>''];
  public $table   = ['class'=>''];
  public $thead   = ['class'=>''];

  public function run(){
    $this->setHeader($this->columns);
    $this->setBody($this->data);

    echo $this->getHtml();
  }

  private function setHeader($columns=[]){
    if($columns!==[]){
      $this->header =  '<thead>';
      $this->header .= $this->getFila($columns);
      $this->header .= '</thead>';
    }
  }

  private function getCelda($c){
    return '<td class="'.$this->td['class'].'">'.$c.'</td>';
  }

  private function getFila($fila=[]){
    $HTML = '<tr class="'.$this->tr['class'].'">';
    foreach ($fila as $v) {
      $HTML .= $this->getCelda($v);
    }
    $HTML .= '</tr>';
    return $HTML;
  }

  private function setBody($filas=[]){
    $HTML =  '<tbody>';
    if ($this->body === ''){
      foreach ($filas as $fila) {
        $HTML .= $this->getFila($fila);
      }
    } else {
      $HTML .= $this->body;
    }
    $HTML .= '</tbody>';
    $this->body = $HTML;
  }

  private function getHtml(){
    return '<table class="'.$this->table['class'].'">'.$this->header.$this->body.'</table>';
  }
}
