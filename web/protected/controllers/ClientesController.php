<?php

class ClientesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('doAccesoCliente'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionDoAccesoCliente()
	{
		extract($_POST);

		$response = false;

		$cliente = Clientes::model()->findByAttributes(array('codigo'=>$usuario,'cuit'=>$password));

		if($cliente) {
			$response = true;
			Yii::app()->session['cliente'] = $usuario;
		}

		header("Content-type: application/json");
        echo CJSON::encode(array(
            'ok' => $response
            )
        );
        exit;

	}

}
