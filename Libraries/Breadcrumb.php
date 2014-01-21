<?php

class Breadcrumb {

	public static::$container = array();

	public function map($items, $key, $value)
	{
		foreach ($items as $item)
		{
			$this->put($item->{$key}, $item->{$value});
		}

		return $this;
	}

	public function output()
	{
		foreach (static::$container	as $item)
		{
			\View::make('_system.breadcumb', compact($item));
		}
	}

	public function put($key, $value)
	{
		static::$container[$key] = $value;
		return $this;
	}

	public function remove($key)
	{
		unset(static::$container[$key]);
		return $this;
	}
}