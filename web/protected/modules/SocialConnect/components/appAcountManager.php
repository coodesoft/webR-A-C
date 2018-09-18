<?php
class appAcountManager {

  private $socialConnector;

  private $arrayResult = [ //arreglo para devolver sobre el resultado de las operaciones
    'newUser'  => false,
    'login'    => false,
    'redirURL' => '', // url para redireccionar por java script
    'errorMsg' => '',
    'userInfo' => '', // solo para pruebas [test]
  ];

  public function __construct($socialConnector){
    $this->socialConnector = $socialConnector;
    $arrayResult['redirURL'] = Yii::app()->createAbsoluteUrl('user/profile/edit');
  }

  //función para loguearse en el sistema y de no existir el user crear una nueva cuenta
  public function login(){
    $res = $this->socialConnector->verifyLogin();

    if (!$res) { // si no se pudo verificar el login
      $this->arrayResult['errorMsg'] .= 'No se pudo verificar el inicio de sesion ';
      return false;
    }

    $this->arrayResult['userInfo'] = $this->socialConnector->getUserData(); //[test]
    $user = $this->getUser(); //buscamos el usuario

    if ($user === NULL) // si es null quiere decir que el usuario n o existe
      $user = $this->createUser();

    if ($user === NULL) { // si sigue siendo null quiere decir que el usaurio no pudo ser creado
      $this->arrayResult['errorMsg'] .= 'El usuario no pudo ser creado';
      return false;
    }


    $this->userlogin($user);
  }

  private function userLogin($user){
    $identity = new SocialUserIdentity($user['id'],$user['username']);

    Yii::app()->user->login($identity,0);
    $this->arrayResult['login'] = true;

    $sc = $this->socialConnector;
    Yii::app()->session['id_social_login'] = $sc::ID_NAME;
  }

  //esta funcion se encarga de buscar el usuario apartir del email
  private function getUser(){
    $email = $this->socialConnector->getEmail();
    return User::model()->notsafe()->findByAttributes(['email'=>$email, 'web' => 1]);
  }

  private function createUser(){
    $user            = new User;
    $user->username  = $this->socialConnector->getNombre().'_'.time();
    $user->password  = User::NO_PASSWORD;
    $user->email     = $this->socialConnector->getEmail();
    $user->web       = 1;
    $user->status    = User::STATUS_ACTIVE;
    $user->superuser = 0;
    $user->save(false);

    $profile            = new Profile; // [Revisar] tambien hay que ver todos los campos que se corresponden al perfil
    $profile->user_id   = $user->id;
    $profile->lastname  = $this->socialConnector->getApellido();
    $profile->firstname = $this->socialConnector->getNombre();
    $profile->save();

    $cliente           = new Clientes();
    $cliente->apellido = $this->socialConnector->getApellido();
    $cliente->nombre   = $this->socialConnector->getNombre();
    $cliente->grupo_id = 2;
    $cliente->user_id  = $user->id;
    $cliente->email    = $user->email;
    $cliente->save();

    $this->arrayResult['newUser'] = true;
    return $user;
  }

  public function getJSONResult(){
    return json_encode($this->arrayResult);
  }
}
 ?>
