<?php
//se modifica el CUserIdentity para trabajar sin autenticación para el login
// en si lo unico que cambia es no pasar contraseña en el constructor y modificar
// el método para mostrar el nombre de usuario para que no se vea el id en los
// lugares en donde se muestra el nombre de usuario
class SocialUserIdentity extends CUserIdentity{
  private $display_name;

  public function __construct($user_id,$display_name)
	{
		$this->username=$user_id;
    $this->display_name=$display_name;
		$this->password='';
	}

  public function getName()
  {
    return $this->display_name;
  }
}
 ?>
