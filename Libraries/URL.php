<?php

class URL extends \Laravel\Url {

	public static function to_upload($route)
	{
		$params = $route['params'] ? : array('object_id' => 0);
		$route = Router::requestId(Controller::$route, 'upload');
		return Router::has($route, 'POST') ? static::to_route($route, $params) : '';
	}
}