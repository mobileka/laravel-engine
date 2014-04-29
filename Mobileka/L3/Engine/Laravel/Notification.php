<?php namespace Mobileka\L3\Engine\Laravel;

use Mobileka\L3\Engine\Laravel\Helpers\Arr;

class Notification {

	/**
	 * A namespace in a session array
	 */
	protected static $namespace = 'notifications.';

	public static $permittedMessageTypes = array('success', 'error', 'warning', 'info');

	/**
	 * Prints all messages
	 *
	 * @param string $view - a view that will output error messages
	 * @param string $id - notification identifier
	 * @return \Laravel\View
	 */
	public static function printAll($view = 'engine::_system.notifications', $id = '')
	{
		return \Laravel\View::make(
			$view,
			array(
				'notifications' => Session::get('notifications', array()),
				'permittedMessageTypes' => static::$permittedMessageTypes,
				'id' => $id
			)
		);
	}

	/**
	 * Sets an error message
	 *
	 * @throws Exception
	 * @param string $name
	 * @param array $arguments
	 * @return mixed
	 */
	public static function __callStatic($name, $arguments)
	{
		if (in_array($name, static::$permittedMessageTypes))
		{
			return Session::flash(static::$namespace . $name . Arr::getItem($arguments, 1, ''), $arguments[0]);
		}

		throw new \Exception("Trying to call an undefined static method \"$name\" of a " . __CLASS__ . ' class');
	}

}
