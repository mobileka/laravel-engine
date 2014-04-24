<?php namespace Mobileka\L3\Engine\Laravel;

use Mobileka\L3\Engine\Laravel\Helpers\Arr;

class Input extends \Laravel\Input {

	public static function except($except)
	{
		$except = is_array($except) ? $except : array($except);
		return Arr::except(static::get(), $except);
	}

	public static function allBut($except)
	{
		return static::except($except);
	}

	public static function safeGet($key = null, $default = null)
	{
		$get = static::get($key, $default);

		if ($get === $default or (is_array($get) and !$get))
		{
			return $default;
		}

		if (!is_array($get))
		{
			return HTML::entities($get);
		}

		return static::recursiveSanitizer($get);
	}

	public static function recursiveSanitizer($sanitizeMe)
	{
		$result = array();

		foreach ($sanitizeMe as $key => $value)
		{
			$result[$key] = is_array($value) 
				? static::recursiveSanitizer($value) :
				HTML::entities($value)
			;
		}

		return $result;
	}

}