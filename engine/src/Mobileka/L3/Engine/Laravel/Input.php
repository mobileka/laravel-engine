<?php namespace Mobileka\L3\Engine\Laravel;

use \Helpers\Arr;

class Input extends \Laravel\Input {

	public static function except($except)
	{
		$except = is_array($except) ? $except : array($except);
		return Arr::except(Input::get(), $except);
	}

	public static function allBut($except)
	{
		return static::except($except);
	}

}