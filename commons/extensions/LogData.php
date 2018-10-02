<?php
class LogData{
  static $logStatic          = '';
  private $log          = '';
  private $isFirstLog   = true;
  private $intentsCount = 0;

  protected $categoriaLog = '';

  public  $maxIntents   = 10;
  public  $srcLog       = '/../../protected/logs/';
  public  $fileName     = 'log';
  public  $extension    = '.txt';

  public function __construct($config = []){

    if (isset($config['categoriaLog']))
      $this->srcLog .= $config['categoriaLog'].'/';

    if (!file_exists($this->getPath())) //comprobamos si existe la ruta
      $created = mkdir($this->getPath(),0775,true);
  }

  private function getPath(){
    return dirname(__FILE__).$this->srcLog;
  }

  private function getFileName(){
    return $this->getPath().$this->fileName.$this->intentsCount.$this->extension;
  }

  private function logHeader(){
    return "\n \n############################\n Fecha y Hora: ".date('Y-m-d H:i:s')."\n";
  }

  public function toLog($l){
    if ($this->isFirstLog){
      $this->isFirstLog = false;
      error_log($this->logHeader(),3,$this->getFileName());
    }
    error_log("\n".$l,3,$this->getFileName());
  }

  public function closeLog($l = ''){
    if($l != '') $this->toLog($l);
  }


  public static function log($l){
      if (self::$logStatic == '')
        self::$logStatic = new LogData;
      self::$logStatic->toLog($l);
    }

}
 ?>
