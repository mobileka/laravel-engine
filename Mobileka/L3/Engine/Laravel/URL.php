<?php namespace Mobileka\L3\Engine\Laravel;

class URL extends \Laravel\Url {

	public static function to_thumbnail($route)
	{
		$params = $route['params'] ? : array('object_id' => 0);
		$route = Router::requestId(\ontroller::$route, 'view_file');
		return Router::has($route, 'GET') ? static::to_route($route, $params) : '';
	}

	public static function to_upload($route)
	{
		$params = $route['params'] ? : array('object_id' => 0);
		$route = Router::requestId(Controller::$route, 'upload_file');
		return Router::has($route, 'POST') ? static::to_route($route, $params) : '';
	}

	public static function to_existing_route($name, $parameters = array(), $method = 'GET')
	{
		return (Router::exists($name, $method) and Acl::make()->checkByAlias($name))
			? static::to_route($name, $parameters)
			: '#'
		;
	}
}
