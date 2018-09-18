<?php

class LoginController extends Controller
{

	public function actionCreated(){
		$this->render('userCreated');
	}

	/////////////////////////////////////////////////////////////////////////////
	/// Inicio de sesión con redes sociales /////////////////////////////////////
	public function actionGoogle(){
		$socialC = new GoogleConnect;

		if(isset($_POST['idtoken'])) {
			$socialC->setTokenData($_POST['idtoken']);

			$appCM   = new appAcountManager($socialC);
			$appCM->login();

			echo $appCM->getJSONResult();
		}
	}


	public function actionFacebook(){ //en el caso de facebook el token no se pasa en peticion
		$socialC = new FacebookConnect;

		$appCM   = new appAcountManager($socialC);
		$appCM->login();

		echo $appCM->getJSONResult();
	}
}
