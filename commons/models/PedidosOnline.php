<?php
  class PedidosOnline{
    private static $FormasPagosOnline = [
      'mp' => [
        'description' => [
          'name'        => 'Mercado Pago',
          'img'         => '/images/icon-payment-02.png',
          'checkout_notes' => 'En el siguiente paso se abrirá una ventana segura de Mercado Pago en donde podrás completar los detalles del pago.',
          'enlacePromo' => 'https://www.mercadopago.com.ar/promociones',
        ],
        'enabled' => true,
      ],

      'tp' => [
        'description' => [
          'name'        => 'Todo Pago',
          'img'         => '/images/icon-payment-01.png',
          'checkout_notes' => 'Al continuar con la compra, estarás ingresando a un sitio seguro de Todo Pago.',
          'enlacePromo' => 'http://www.todopago.com.ar/promociones-vigentes',
        ],
        'enabled' => true,
      ],

      'tbd' => [
        'description' => [
          'name'    => 'Transferencia Bancaria Directa',
          'img'     => '/images/icon-payment-06.png',
        ],
        'enabled' => true,
      ],

    ];

    public static function getFormasPagoOnline(){
      return $this::$FormasPagosOnline;
    }

    public static function isEnabledFormaPagoOnline($cod){
      return $this::$FormasPagosOnline[$cod]['enabled'];
    }

    public static function getFormasPagoData($claves=[]){
      $salida = [];
      foreach (self::$FormasPagosOnline as $k => $v) {
        if (self::$FormasPagosOnline[$k]['enabled']){
          if ($claves == []){ // se entrega toda la descripcion
            $salida[$k] = $v['description'];
          } else { // se entregan solo las claves solicitadas
            foreach ($claves as $fk) {
              $salida[$k][$fk] = $v['description'][$fk];
            }
          }
        }
      }

      return $salida;
    }
  }
 ?>
