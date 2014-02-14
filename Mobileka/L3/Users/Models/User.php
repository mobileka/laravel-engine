<?php namespace Users\Models;

use Mobileka\L3\Engine\Laravel\Base\Model;

class User extends Model {

	public static $hidden = array('password', 'recovery_token', 'recovery_password', 'recovery_request_date');

	public static $rules = array(
		'email' => 'required|email|unique:users',
		'password' => 'required|min:6',
	);

	public function group()
	{
		return $this->belongs_to('\Users\Models\Group');
	}

	public function city()
	{
		return $this->belongs_to('\Cities\Models\City');
	}

	public function orders()
	{
		return $this->has_many('\Orders\Models\Order');
	}

	public function beforeSave()
	{
		if ($this->changed('password'))
		{
			$this->password = \Hash::make($this->password);
		}

		return parent::beforeSave();
	}

	public function saveData($data = array(), $safe = array())
	{
		$this->setAttr($data, 'email');
		$this->setAttr($data, 'password');
		$this->setAttr($data, 'group_id');
		$this->setAttr($data, 'name');
		$this->setAttr($data, 'contacts');
		$this->setAttr($data, 'phone');
		$this->setAttr($data, 'city_id');
		$this->setAttr($data, 'car');
		$this->setAttr($data, 'address');

		return $this->save();
	}
}
