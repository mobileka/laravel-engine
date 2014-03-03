<?php namespace Mobileka\L3\Users\Models;

use Mobileka\L3\Engine\Laravel\Base\Model;

class Group extends Model {

	public static $table = 'user_groups';

	public static $rules = array(
		'name' => 'required|unique:user_groups',
		'code' => 'required|unique:user_groups|alpha_dash'
	);

	public function users()
	{
		return $this->has_many(\IoC::resolve('UserModel'));
	}

	public function beforeValidation()
	{
		$this->code = \Str::lower(trim($this->code));
		return parent::beforeValidation();
	}

	public static function getIdByCode($code)
	{
		return static::where_code($code)->first()->id;
	}
}
