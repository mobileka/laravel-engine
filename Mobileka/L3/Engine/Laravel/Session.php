<?php namespace Mobileka\L3\Engine\Laravel;

class Session extends \Laravel\Session {

	public static function attach($key, $index, $value)
	{
		$old = static::get($key, array());
		$old[$index] = $value;
		return static::put($key, $old);
	}

	public static function detach($key, $index)
	{
		$old = static::get($key, array());
		unset($old[$index]);
		return static::put($key, $old);
	}

	public static function increase($key, $by = 1)
	{
		return static::put($key, ((int)static::get($key, 0) + $by));
	}

	public static function decrease($key, $by = 1)
	{
		return static::put($key, ((int)static::get($key, 0) - $by));
	}

}