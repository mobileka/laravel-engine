<?php namespace Mobileka\L3\Engine\Laravel\Base;

use Mobileka\L3\Engine\Laravel\Str;

class BClass {

	public static function make()
	{
		return new static;
	}

	public function __call($method, $args)
	{
		if (method_exists($this, Str::snakeToCamel($method)))
		{
			return call_user_func_array(array($this, $method), $args);
		}

		throw new \Exception("Call to undefined method $method of a " . get_class($this) . ' class');
	}

	public function __get($name)
	{
		$name = Str::camelToSnake($name);

		if (property_exists($this, $name))
		{
			return $this->{$name};
		}

		throw new \Exception("Trying to get an undefined property $name of a " . get_class($this) . ' class');
	}
}