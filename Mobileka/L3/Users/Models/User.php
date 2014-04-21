<?php namespace Mobileka\L3\Users\Models;

use Mobileka\L3\Engine\Laravel\Base\Model;

class User extends Model {

	public static $hidden = array(
		'password',
		'recovery_token',
		'recovery_password',
		'recovery_request_date'
	);

	public static $rules = array(
		'email' => 'required|email|unique:users',
		'password' => 'required|min:8',
	);

	public function group()
	{
		return $this->belongs_to(\IoC::resolve('UserGroupModel'));
	}

	public function get_fullname()
	{
		return $this->name ? : 'Имя не указано';
	}

	public function beforeValidation()
	{
		if ($this->exists and $this->password === '')
		{
			$this->password = $this->original['password'];
		}

		return parent::beforeValidation();
	}

	public function afterValidation()
	{
		if ($this->changed('password') or !$this->exists)
		{
			$this->password = \Hash::make($this->password);
		}

		return parent::afterValidation();
	}
}
