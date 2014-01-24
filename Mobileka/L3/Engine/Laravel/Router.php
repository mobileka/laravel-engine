<?php namespace Mobileka\L3\Engine\Laravel;

class Router extends \Laravel\Routing\Router {

	/**
	 * Checks if an alias is registered in the Router
	 *
	 * @param string $alias
	 * @param string $method
	 * @return bool
	 */
	public static function has($alias, $method = 'GET')
	{
		$routes = \Arr::permissivePluck(static::$routes[$method], 'as');
		return in_array($alias, $routes);
	}

	/**
	 * Checks whether a provided $route
	 * is currently being processed by the Request
	 *
	 * @param string $route
	 * @return bool
	 */
	public static function isCurrentRoute($route)
	{
		if ($currentRoute = \Arr::getItem(\Controller::$route, 'alias'))
		{
			return $currentRoute === $route;
		}

		return false;
	}

	/**
	 * Return a unique id of a current request (bundle_submodule_controller_(:action))
	 *
	 * @param array $route
	 * @param bool|string $action - do we need an action name to be appended at the end of the line?
	 * @return string
	 */
	public static function requestId(Array $route, $action = false)
	{
		$route = $route ? : \Controller::$route;

		$result = str_replace('.', '_', $route['controller']);

		if ($bundle = \Arr::getItem($route, 'bundle'))
		{
			$result = $bundle . '_' . $result;
		}

		if ($action === true)
		{
			$result .= '_' . $route['action'];
		}
		elseif ($action)
		{
			$result .= '_' . $action;
		}

		return $result;
	}

	/**
	 * Translate route URI wildcards into regular expressions.
	 *
	 * @param  string  $key
	 * @return string
	 */
	public static function wildcards($key)
	{
		return parent::wildcards($key);
	}
}