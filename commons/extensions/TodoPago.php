<?php
/**
 * Created by PhpStorm.
 * User: pablo
 * Date: 10/07/16
 * Time: 11:48
 */
class TodoPago
{
    const MODE = "prod";

    const MERCHANTPROD = '4742';
    const AUTHKEYPROD = 'PRISMA 6183707F0EEB829BAC551318382CF919';
    const SECURITYPROD = '6183707F0EEB829BAC551318382CF919';

    const MERCHANTTEST = '2323';
    const AUTHKEYTEST = 'TODOPAGO a414b821c9034137a566d6f1ec077578';
    const SECURITYTEST = 'a414b821c9034137a566d6f1ec077578';

    const URL_OK = 'http://gpdesarrollos.com.ar/rac/web/productos/exito?operationid=';
    const URL_KO = 'http://gpdesarrollos.com.ar/rac/web/productos/fail?operationid=';

    public $merchant;
    public $authKey;
    public $security;

    public $operationId;

    public function __construct()
    {
        if (self::MODE == 'prod') {
            $this->merchant = self::MERCHANTPROD;
            $this->authKey = self::AUTHKEYPROD;
            $this->security = self::SECURITYPROD;
        } else if (self::MODE == 'test') {
            $this->merchant = self::MERCHANTTEST;
            $this->authKey = self::AUTHKEYTEST;
            $this->security = self::SECURITYTEST;
        }
    }

    public function prepareForm($items)
    {
        $form = [];
        if ($items !== null) {
            foreach ($items as $item) {
                if ($item->producto) {
                    $form['descripciones'][] = TextHelper::character_limiter($item->producto['descripcion'], 20) ?: 'Descripción del producto no disponible.';
                    $form['unitarios'][] = $item->producto->precio[ProductosPrecios::PRECIO_ONLINE_ID]['precio'];
                    $form['cantidades'][] = $item->cantidad;
                    $form['etiquetas'][] = $item->producto->etiqueta;
                    $form['ids'][] = $item->producto->categoria->categoria_id.'_'.$item->producto->producto_id;
                    $form['code'][] = 'electronic_good';
                }
            }

            $form['unitarios'] = implode('#', $form['unitarios']);
            $form['cantidades'] = implode('#', $form['cantidades']);
            $form['etiquetas'] = implode('#', $form['etiquetas']);
            $form['ids'] = implode('#', $form['ids']);
            $form['descripciones'] = implode('#', $form['descripciones']);
            $form['code'] = implode('#', $form['code']);
            $form['total'] = $items['total'];
        }

        return $form;
    }

    public static function getResponse()
    {
        $tp = new TodoPago();

        $rk = Yii::app()->session['RequestKey'];
        $ak = $_GET['Answer'];
        $operationid = $_GET['operationid'];
        $optionsGAA = array (
            'Security'   => $tp->security,
            'Merchant'   => $tp->merchant,
            'RequestKey' => $rk,
            'AnswerKey'  => $ak // *Importante
        );
        //común a todas los métodos
        $http_header = array('Authorization'=>$tp->authKey);
        //creo instancia de la clase TodoPago
        $connector = new Sdk($http_header, TodoPago::MODE);
        $rta2 = $connector->getAuthorizeAnswer($optionsGAA);

        return $rta2;
    }
}