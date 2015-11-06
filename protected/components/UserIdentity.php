<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{	
	private $_id;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{	
		$user=User::model()->find("LOWER(user_email)=?",array(strtolower($this->username)));
		if( $user===null) {
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		elseif($user->user_password !== $this->password) {
			$user->user_failed_login_attempts++;
			$user->save();
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		} 
		elseif($user->user_active != 1 || $user->user_failed_login_attempts >= 3){
			$user->user_failed_login_attempts++;
			$user->save();
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}
		else {
			$user->user_failed_login_attempts = 0;
			$user->save();
			$this->_id = $user->user_id;
			$this->errorCode=self::ERROR_NONE;
		}
		return !$this->errorCode;
	}

	public function getId(){
		return $this->_id;
	}

}