<?php
/**
 * UserChangePassword class.
 * UserChangePassword is the data structure for keeping
 * user change password form data. It is used by the 'changepassword' action of 'UserController'.
 */
class UserChangePassword extends CFormModel {
	public $oldPassword;
	public $password;
	public $verifyPassword;

	public function rules() {
		if (Yii::app()->controller->id == 'recovery'){
			return [
								['password, verifyPassword', 'required'],
								['password, verifyPassword', 'length', 'max'=>128, 'min' => 4,'message' => UserModule::t("Incorrect password (minimal length 4 symbols).")],
								['verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => UserModule::t("Las contraseñas no coinciden.")],
						 ];
		}

		if (Yii::app()->controller->id == 'profile'){
			return [
								['password, verifyPassword', 'required'],
								['password, verifyPassword', 'length', 'max'=>128, 'min' => 4,'message' => UserModule::t("Incorrect password (minimal length 4 symbols).")],
								['verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => UserModule::t("Las contraseñas no coinciden.")],
						 ];
		}

		//si no se cumplieron las condiciones anteriores estas son las reglas por defecto
		return [
			['oldPassword, password, verifyPassword', 'required'],
			['oldPassword, password, verifyPassword', 'length', 'max'=>128, 'min' => 4,'message' => UserModule::t("Incorrect password (minimal length 4 symbols).")],
			['verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => UserModule::t("Las contraseñas no coinciden.")],
			['oldPassword', 'verifyOldPassword'],
		];

	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return [
			'oldPassword'    => UserModule::t("Old Password"),
			'password'       => UserModule::t("password"),
			'verifyPassword' => UserModule::t("Retype Password"),
		];
	}

	/**
	 * Verify Old Password
	 */
	 public function verifyOldPassword($attribute, $params)
	 {
		 if (User::model()->notsafe()->findByPk(Yii::app()->user->id)->password != Yii::app()->getModule('user')->encrypting($this->$attribute))
			 $this->addError($attribute, UserModule::t("La contraseña ingresada no es válida."));
	 }
}
