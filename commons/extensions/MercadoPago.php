<?php
/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 10/07/16
 * Time: 11:48
 */
class MercadoPago
{
    const SANDBOX_MODE = false;

    const CLIENT_ID = '195005754821948';
    const SECRET_ID = 'liaTKXwQarR4ICiJOHO8qxwDnIzGR1cU';

    const CLIENT_ID_TEST = '6225732034898730';
    const SECRET_ID_TEST = 'zB0jH3QnbJF8H5F9v54Fmks3AqYxFQpS';

    public $client_id;
    public $secret_id;
    public $process_url_key;

    public $operationId;

    public $URL_OK = '';
    public $URL_KO = '';
    public $URL_PEND = '';
    public $URL_IPN = '';

    protected $log = '';

    public function __construct() {
        
        $mode = Yii::app()->params['mode'];

        if ($mode == 'prod') {
            $this->client_id = self::CLIENT_ID;
            $this->secret_id = self::SECRET_ID;
        } else if ($mode == 'test') {
            $this->client_id = self::CLIENT_ID_TEST;
            $this->secret_id = self::SECRET_ID_TEST;
        }

        if (self::SANDBOX_MODE) {
            $this->process_url_key = 'sandbox_init_point';
        } else {
            $this->process_url_key = 'init_point';
        }

        $url = Yii::app()->params[$mode]['URL'];
        $this->URL_OK = $url.'/productos/exitomp';
        $this->URL_KO = $url.'/productos/failmp';
        $this->URL_PEND = $url.'/productos/pendingmp';
        $this->URL_IPN = $url.'/productos/ipnmp';

    }

    public static function getMPSDK(){
        require_once Yii::app()->basePath.'/extensions/mercadoPago/lib/mercadopago.php';  
        $mode = Yii::app()->params['mode'];
        if ($mode == 'prod') {
            return new MP(MercadoPago::CLIENT_ID, MercadoPago::SECRET_ID);
        } else if ($mode == 'test') {
            return new MP(MercadoPago::CLIENT_ID_TEST, MercadoPago::SECRET_ID_TEST);
        }
        
    }

    //funciones de logueo
    protected function toLog($l){
      if ($this->log == '')
        $this->log = new LogData;
      $this->log->toLog($l);
    }

    protected function closeLog($l = ''){
      $this->log->closeLog($l);
    }
}
