<?php

class LogoutController extends Controller
{
	public $defaultAction = 'logout';

	/**
	 * Logout the current user and redirect to returnLogoutUrl.
	 */
	public function actionLogout()
	{
		$redirectUrl = Yii::app()->controller->module->returnLogoutUrl;

		//verificamos si se trata de una sesion vinculada a red social
		
		/*if (isset(Yii::app()->session['id_social_login'])){
				$urlSocial = SocialConnectModule::getLogOutUrl(Yii::app()->session['id_social_login']);
				if ($urlSocial != '')
					$redirectUrl = $urlSocial;
		}*/

		UserModule::regLogOut();
		Yii::app()->user->logout();

		$this->redirect($redirectUrl);

	}

	/**
	 * Logout the current user and redirect to returnLogoutUrl.
	 */
	public function actionLogoutAjax()
	{
		$redirectUrl = Yii::app()->controller->module->returnLogoutUrl;

		//verificamos si se trata de una sesion vinculada a red social
		
		/*if (isset(Yii::app()->session['id_social_login'])){
				$urlSocial = SocialConnectModule::getLogOutUrl(Yii::app()->session['id_social_login']);
				if ($urlSocial != '')
					$redirectUrl = $urlSocial;
		}*/

		UserModule::regLogOut();
		Yii::app()->user->logout();

		echo json_encode(["success" => true]);
	}

}
