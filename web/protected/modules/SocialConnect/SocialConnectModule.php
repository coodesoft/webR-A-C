<?php

class SocialConnectModule extends CWebModule
{
	public static $socialConectors = [
		'facebook' => FacebookConnect,
		'google'   => GoogleConnect,
	];

	const MY_URL = 'https://rac.geneos.com.ar/web';

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport([
			'SocialConnect.models.*',
			'SocialConnect.components.*',
		]);
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}

	public static function getLogOutUrl($id){
		$socialConector = self::$socialConectors[$id];
		return $socialConector::getLogOutUrl();
	}

	public static function getMetaTags($id){
		$socialConector = self::$socialConectors[$id];
		return $socialConector::getMetaTags();
	}

	public static function getJavaScript($id){
		$socialConector = self::$socialConectors[$id];
		return $socialConector::getJavaScript();
	}

	public static function getHTMLLoginButton($id,$params = []){
		$socialConector = self::$socialConectors[$id];
		return $socialConector::getHTMLLoginButton($params);
	}

	public static function getHTMLLogoutButton(){

		if (isset(Yii::app()->session['id_social_login'])){
					$socialConector = self::$socialConectors[Yii::app()->session['id_social_login']];
					return $socialConector::getHTMLLogoutButton();
		}
		else
			return '<a href="'.Yii::app()->createAbsoluteUrl('/user/logout').'">Salir</a>';

	}
	
}
