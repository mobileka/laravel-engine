<?php namespace Mobileka\L3\Engine\Laravel\Helpers;

use Mobileka\L3\Engine\Laravel\Str;

/**
 * @author Armen Markossyan <a.a.markossyan@gmail.com>
 * @version 1.0
 */
class Misc {

	/**
	 * Get information about current request (route) in an array
	 *
	 * @return array
	 */
	public static function currentRoute()
	{
		$route = \Laravel\Request::route();

		$uses = Arr::getItem($route->action, 'uses', '');
		$alias = Arr::getItem($route->action, 'as', '');
		$bundle = '';

		if ($uses)
		{
			if (strpos($route->action['uses'], '::') !== false)
			{
				list($bundle, $uses) = explode('::', $uses);
			}

			list($controller, $action) = explode('@', $uses);
		}
		else
		{
			$bundle = (isset($route->bundle) and $route->bundle != 'application') ? $route->bundle : '';
			$controller = $route->controller;
			$action = $route->controller_action;
		}

		$result = array(
			'alias' => $alias,
			'uses' => Arr::getItem($route->action, 'uses', ''),
			'bundle' => $bundle,
			'controller' => $controller,
			'action' => $action,
			'parameters' => $route->parameters,
			'params' => '',
			'query_string' => ($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : ''
		);

		for ($i = 0, $count = count($result['parameters']); $i < $count; $i++)
		{
			if ($i > 0)
			{
				$result['params'] .= '/';
			}

			$result['params'] .= $result['parameters'][$i];
		}

		return $result;
	}

	/**
	 * Generate a URI suitable for Route::to_action() and other to_action() methods
	 *
	 * @param array $route consists of three keys ['controller' => '', 'action' => '', 'bundle' => '']
	 * @return string
	 */
	public static function actionUri(Array $route)
	{
		$controller = array_key_exists('controller', $route) ? $route['controller'] : '';
		$action = array_key_exists('action', $route) ? $route['action'] : '';
		$bundle = array_key_exists('bundle', $route) ? $route['bundle'] : '';

		$uri = $controller;

		if ($action)
		{
			$uri = $controller . '@' . $action;
		}

		if ($bundle)
		{
			$uri = $bundle . '::' . $uri;
		}

		return $uri;
	}

	/**
	 * Generate a URL to action by route
	 *
	 * @param array $route consists of three keys ['controller' => '', 'action' => '', 'bundle' => '']
	 * @param array $params
	 * @return string
	 */
	public static function url(Array $route, $params = array())
	{
		$uri = static::actionUri($route);
		return \Laravel\URL::to_action($uri, $params);
	}



	/**
	 * Return a value if it is truthy.
	 * Return defaultValue otherwise
	 *
	 * @param mixed $value
	 * @param mixed $defaultValue
	 * @return array
	 */
	public static function truthyValue($value, $defaultValue = null)
	{
		if ($value)
		{
			return $value;
		}

		return $defaultValue;
	}

	/**
	 * Return a property value if it exists in a given object.
	 * Return defaultValue otherwise
	 *
	 * @param string $pattern
	 * @param array $array
	 * @param mixed $flags
	 * @return array
	 */
	public static function existingProperty($object, $property, $defaultValue = null)
	{
		if (property_exists($object, $property))
		{
			return $object->{$property};
		}

		return $defaultValue;
	}


	/**
	 * Tries to convert eny given "thing" to a plain data (arrays, strings, integers)
	 * This can be useful when you need to convert something to JSON and
	 * you don't know what is in the "thing". More realistically, you should
	 * use this method when you need to convert to JSON an array which
	 * contains Eloquent Models or an array of such models
	 *
	 * @param mixed $what
	 * @return array
	 */
	public static function prepareForJson($what)
	{
		$result = array();

		if (is_array($what))
		{
			foreach ($what as $object)
			{
				$result[] = static::prepareForJson($object);
			}
		}
		elseif ($what instanceof \Laravel\Paginator)
		{
			$result = static::prepareForJson($what->results);
		}
		elseif ($what instanceof \Laravel\Database\Eloquent\Model)
		{
			$result = $what->to_array();
		}

		return $result;
	}

	/**
	 * Return a path to a config or language file for
	 * a current request
	 *
	 * @param string $concat
	 * @return string
	 */
	public static function filePath($file = 'default', $concat = '')
	{
		$route = static::currentRoute();

		if ($bundle = Arr::getItem($route, 'bundle'))
		{
			$file = $bundle . '::' . $file;
		}

		return $file . $concat;
	}

	/**
	 * Get a value of any property of any class
	 *
	 * @param object|string $reflect_me
	 * @param string $property
	 * @return mixed
	 */
	public static function propertyValue($reflect_me, $property)
	{
		$reflectedClass = new \ReflectionClass($reflect_me);
		$property = $reflectedClass->getProperty($property);
		$property->setAccessible(true);
		return $property->getValue($reflect_me);
	}

	/**
	 * Invoke any method of any class
	 *
	 * @param string $class
	 * @param string $method
	 * @param mixed $params
	 * @return mixed
	 */
	public static function invokeMethod($class, $method, $params = null)
	{
		$reflectedMethod = new \ReflectionMethod($class, $method);
		$reflectedMethod->setAccessible(true);
		return $reflectedMethod->invokeArgs(new $class, $params);
	}

	/**
	 * Generate a random password
	 *
	 * @param int $length
	 * @return string
	 */
	public static function randomPassword($length = 8, $characters = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890!@#$%^&*()')
	{
		$password = '';
		$size = Str::length($characters);
		$randomizer = (string)microtime();

		for ($i = 0; $i < $length; $i++)
		{
			$password .= $characters[rand(0, $size - 1)] . $randomizer[rand(2, 9)];
		}

		return Str::limit($password, $length, '');
	}
}

