<?php namespace Mobileka\L3\Engine\Laravel;

class Redirect extends \Laravel\Redirect {

	/**
	 * Create a redirect response to the page referred before a current request.
	 *
	 * @param  int       $status
	 * @return Redirect
	 */
	public static function back($status = 302)
	{
		$url = Session::get('acl: last_blocked_url', \Laravel\Request::referrer());
		Session::forget('acl: last_blocked_url');
		return static::to($url, $status);
	}

	/**
	 * A convinient way to add a notification to a redirect
	 *
	 * @param string $message - a messageto be shown to a user
	 * @param string $type - notification type
	 * @param string $id - identifier
	 * @return \Redirect
	 */
	public function notify($message, $type = 'info', $id = '')
	{
		Notification::$type($message, $id);
		return $this;
	}

	/**
	 * If an undefined method being called is a Notification
	 * message type, send a notification of this type
	 * instead of showing an error.
	 *
	 * Example: return Redirect::to_route('blah')->success('Blaaaah!');
	 *
	 * success() method is undefinied in Redirect class but it is
	 * a Notification type
	 *
	 * @param string $name
	 * @param array $args
	 * @return mixed
	 */
	public function __call($name, $args)
	{
		if (in_array($name, Notification::$permittedMessageTypes))
		{
			return $this->notify($args[0], $name);
		}

		return parent::__call($name, $args);
	}

}