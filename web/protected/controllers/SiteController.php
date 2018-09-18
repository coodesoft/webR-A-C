<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	public function actionEmpresa()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('empresa');
	}

	public function actionClientes()
	{
		$model=new ClientesForm;
		if(isset($_POST['ClientesForm']))
		{
			$model->attributes=$_POST['ClientesForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/html; charset=UTF-8";
				$model->body .= "<br /><br />Nombre y apellido: " . $model->name . "<br />Email: " . $model->email . "<br />CUIT: " . $model->cuit;
				mail(Yii::app()->params['contactoEmail'],'Contacto desde la sección de clientes de la web',$model->body,$headers);
				Yii::app()->user->setFlash('contactClientes','Gracias por contactarse. Le responderemos a la brevedad.');
				$this->refresh();
			}
		}
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('clientes',['model'=>$model]);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContacto()
	{

		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
		        
		        EMailer::sendContactMail($model);

				Yii::app()->user->setFlash('contacto','Gracias por contactarse. Le responderemos a la brevedad.');
				$this->refresh();
			}
		}
		$this->render('contacto',array('model'=>$model,'producto'=>$producto));
	}

	/**
	 * Displays the contact page
	 */
	public function actionContactoProducto($id1, $id2)
	{
		if (isset($id1) && isset($id2)) {
			$producto = Productos::getProductInfo($id1, $id2);
			if ($producto === null) 
            	throw new CHttpException(404,'The requested page does not exist.');
		}

		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{

				EMailer::sendContactFromProductMail($model,$producto);

				Yii::app()->user->setFlash('contacto','Gracias por contactarse. Le responderemos a la brevedad.');
				$this->refresh();
			}
		}
		$this->render('contacto',array('model'=>$model,'producto'=>$producto));
	}

}
