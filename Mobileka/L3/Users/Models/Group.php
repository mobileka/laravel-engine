<?php namespace Users\Models;

use Mobileka\L3\Engine\Laravel\Base\Model;

class Group extends Model {

	public static $table = 'user_groups';

	public static $types = array(
		'controlPanel' => array('programmers', 'admins', 'contents'),
		'managers' => array('programmers', 'admins', 'managers', 'contents')
	);

	public static $rules = array(
		'name' => 'required|unique:user_groups',
		'code' => 'required|unique:user_groups|alpha_dash'
	);

	public function users()
	{
		return $this->has_many('User');
	}

	public function onSave()
	{
		$this->code = \Str::lower(trim($this->code));
		return true;
	}

	public static function getIdByCode($code)
	{
		return static::where_code($code)->first()->id;
	}
}
