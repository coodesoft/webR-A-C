<?php
require_once(Yii::app()->basePath.'/../../commons/extensions/google-api-php-client-2.2.1/vendor/autoload.php');

class GoogleConnect {
  public static $APIData = [
		'proyect_id'      => 'rosarioalcosto-191223',
		'proyect_number'  => '702798243761',
		'client_id'       => '702798243761-qai8u8ur8si4lhmtq1g3k4i0lkurjo9a.apps.googleusercontent.com',
		'client_secret'   => 'g_kxdaNEVo0yvyLtP06XzlJv',
		'actionLogin'     => 'SocialConnect/login/Google',

	];

  const ID_NAME = 'google';

  private $userData;
  private $token;

  public static function getUrlLogin() { return Yii::app()->createAbsoluteUrl(self::$APIData['actionLogin']);}

  public static function getUrlLogout() { return Yii::app()->createAbsoluteUrl('user/logout/logoutAjax');}

	public static function getClientId() { return self::$APIData['client_id']; }

  public static function getMetaTags() {
    return '<meta name="google-signin-client_id" content="'.self::$APIData['client_id'].'">' ;
  }

  public function verifyToken(){
    $client = new Google_Client(['client_id' => self::getClientId()]);
    $payload = $client->verifyIdToken($this->getToken());

    if ($payload) {
      $this->userData = $payload;
      return true;
    } else {
      return false;
    }
  }

  public static function getLogOutUrl(){
    return 'https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue='.SocialConnectModule::MY_URL;
  }

  public static function getHTMLLogoutButton(){
     return '<a class="google-logout" onClick="googleSignOut()" href="#">Salir</a>';
  }

  public static function getHTMLLoginButton($params=[]){
   // return '<div class="g-signin2" data-width="282" data-onsuccess="onSignIn">Ingresar con Google</div>';
    return '<a href="#" id="google-login" class="gl-login">
              <span class="icon-google-plus-2"></span>
              <span class="gl-login-text">Ingresar con Google</span>
            </a>';
  }

  public static function getJavaScript(){ //esta parte esta demasiado cachiva luego hay que separar el script..
      return "
      <script>

      function onSignIn(googleUser) {
        let profile = googleUser.getBasicProfile();

        let xhr = new XMLHttpRequest();
        xhr.open('POST', '".self::getUrlLogin()."');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
          let r = JSON.parse(xhr.responseText);
          window.location = r['redirURL'];
        };
        xhr.send('idtoken=' + googleUser.getAuthResponse().id_token);

        console.log('logged');
      }

      function googleSignOut() {
        var auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(function () {
          console.log('User signed out. Proceso de cerrar sesion en web');

          let xhr = new XMLHttpRequest();
          xhr.open('POST', '".self::getUrlLogout()."');
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.onload = function() {
            let r = JSON.parse(xhr.responseText);
            if (r.success)
              location.reload();
          };
          xhr.send();
        });
      }

      function googleOnLoad() {

        gapi.load('auth2', function(){
          // Retrieve the singleton for the GoogleAuth library and set up the client.
          auth2 = gapi.auth2.init();
          attachSignin( document.getElementsByClassName('gl-login') );
        });

      }

      function attachSignin(element) {
        for (i = 0; i < element.length; i++) { 
          auth2.attachClickHandler(element[i], {},
            onSignIn, function(error) {
              alert(JSON.stringify(error, undefined, 2));
            });
        }
      }

      </script>";
  }

  public function verifyLogin(){
    return $this->verifyToken();
  }

  public function getUserData(){
    return $this->userData;
  }

  public function getApellido(){ return $this->userData['family_name']; }
  public function getNombre()  { return $this->userData['given_name'];  }
  public function getEmail()   { return $this->userData['email'];       }

  public function setTokenData($token){
    $this->token = $token;
  }

  public function getToken(){
    return $this->token;
  }
}


 ?>
