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

}