<?php namespace Mobileka\L3\Engine\Laravel;

use Mobileka\L3\Engine\Laravel\Base\Controller;
use Mobileka\L3\Engine\Laravel\Helpers\Arr;

class Config extends \Laravel\Config {
	/**
	 * Parse a key and return its bundle, file, and key segments.
	 *
	 * Configuration items are named using the {bundle}::{file}.{item} convention.
	 *
	 * @param  string  $key
	 * @return array
	 */
	protected static function parse($key)
	{
		// First, we'll check the keyed cache of configuration items, as this will
		// be the fastest method of retrieving the configuration option. After an
		// item is parsed, it is always stored in the cache by its key.
		if (array_key_exists($key, static::$cache))
		{
			return static::$cache[$key];
		}

		$bundle = Bundle::name($key);

		$segments = explode('.', Bundle::element($key));

		// If there are not at least two segments in the array, it means that the
		// developer is requesting the entire configuration array to be returned.
		// If that is the case, we'll make the item field "null".
		if (count($segments) >= 2)
		{
			$parsed = array($bundle, $segments[0], implode('.', array_slice($segments, 1)));
		}
		else
		{
			$parsed = array($bundle, $segments[0], null);
		}

		return static::$cache[$key] = $parsed;
	}

	/**
	 * Get a configuration item.
	 *
	 * If no item is requested, the entire configuration array will be returned.
	 *
	 * <code>
	 *		// Get the "session" configuration array
	 *		$session = Config::get('session');
	 *
	 *		// Get a configuration item from a bundle's configuration file
	 *		$name = Config::get('admin::names.first');
	 *
	 *		// Get the "timezone" option from the "application" configuration file
	 *		$timezone = Config::get('application.timezone');
	 * </code>
	 *
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return array
	 */
	public static function find($key, $default = null)
	{
		if (strpos($key, '::') === false and $bundle = Arr::getItem(Controller::$route, 'bundle', false))
		{
			$result = static::get($bundle . '::' . $key, 'blah________________blah');

			if ($result !== 'blah________________blah')
			{
				return $result;
			}
		}

		return static::get($key, $default);
	}
}
