<?php
require_once(Yii::app()->basePath.'/../../commons/extensions/Facebook/autoload.php');

class FacebookConnect {

  public static $APIData = [
		'app_id'                => '214727792411897',
  	'app_secret'            => 'a7244b211d06939e0743370d0a24f865',
  	'default_graph_version' => 'v2.2',
    'actionLogin'           => 'SocialConnect/login/Facebook',
	];

  const ID_NAME = 'facebook';
  private $nombreApellido;
  private $id;
  private $email;



  public static function getUrlLogout() { return Yii::app()->createAbsoluteUrl('user/logout/logoutAjax');}

  public static function getUrlLogin() { return Yii::app()->createAbsoluteUrl(self::$APIData['actionLogin']);}

  public static function getMetaTags() {}

  public static function getLogOutUrl(){
    return '';
  }

  public static function getHTMLLogoutButton(){
     return '<a onClick="logOutWithFacebook()" class="facebook-logout" href="#">Salir</a>';
  }

  public static function getHTMLLoginButton($params=[]){
    return '<a class="fb-login" href="#" onClick="logInWithFacebook()"><span class="icon-facebook"></span><span class="fb-login-text">Ingresar con Facebook</span></a>';
  /*  <div class="fb-login-button" data-max-rows="1"
          data-size="large" data-button-type="login_with"
          data-show-faces="false" data-auto-logout-link="true"
          data-use-continue-as="false"></div>';*/
  }

  public static function getJavaScript(){ //esta parte esta demasiado cachiva luego hay que separar el script..
    return "<script>

      logInWithFacebook = function() {
        FB.login(function(response) {
          let xhr = new XMLHttpRequest();
          xhr.open('POST', '".self::getUrlLogin()."');
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.onload = function() {
            let r = JSON.parse(xhr.responseText);
            window.location = r['redirURL'];
          };
          xhr.send();
        },{ scope: 'email' });
       
        return false;
      };

      logOutWithFacebook = function() {
        if (FB.getAuthResponse()) {

          FB.logout(function(response) {
            
            console.log('User signed out. Proceso de cerrar sesion en web');
            logOutAjax();
          });
        }
        else {
          logOutAjax();
        }
        return false;
      };

      function logOutAjax() {
          let xhr = new XMLHttpRequest();
          xhr.open('POST', '".self::getUrlLogout()."');
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.onload = function() {
            let r = JSON.parse(xhr.responseText);
            if (r.success)
              location.reload();
          };
          xhr.send();
      }

      window.fbAsyncInit = function() {
        FB.init({
          appId: '".self::$APIData['app_id']."',
          cookie: true,
          version: 'v2.2'
        });
      };

    </script>";
  }

  public function verifyLogin(){
    $fb = new Facebook\Facebook(self::$APIData);

		$helper = $fb->getJavaScriptHelper();

		try {
			$accessToken = $helper->getAccessToken();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			return false;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			return false;
		}

		if (! isset($accessToken)) {
			echo 'No cookie set or no OAuth data could be obtained from cookie.';
      return false;
		}

		// Logged in
		Yii::app()->session['fb_access_token'] = (string) $accessToken;

    try {
      // Returns a `Facebook\FacebookResponse` object
      $response = $fb->get('/me?fields=id,name,email', $accessToken);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      echo 'Graph returned an error: ' . $e->getMessage();
      return false;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      return false;
    }

    $user = $response->getGraphUser();

    $this->nombreApellido = $user['name'];
    $this->id = $user['id'];
    $this->email = $user['email'];

    return true;
  }

  public function verifyToken(){

  }

  public function getEmail(){
    return $this->email;
  }

  public function getApellido(){
    return explode(' ',$this->nombreApellido)[1];
  }

  public function getNombre(){
    return explode(' ',$this->nombreApellido)[0];
  }

  public function getUserData(){

  }

  public function setTokenData(){

  }

}


 ?>
