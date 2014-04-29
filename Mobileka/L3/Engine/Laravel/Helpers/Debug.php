<?php namespace Mobileka\L3\Engine\Laravel\Helpers;

/**
 * @author Armen Markossyan <a.a.markossyan@gmail.com>
 * @version 1.0
 */
class Debug {

	/**
	 * Pretty dumper
	 *
	 * @param mixed $what
	 * @param bool $exit
	 * @return void
	 */
	public static function pd($what, $exit = true)
	{
		header('Content-Type: text/html; charset=utf-8');
		$bt = debug_backtrace();
		$caller = array_shift($bt);

		echo $caller['file'], ', line: ', $caller['line'];

		echo '<pre>';
		var_dump($what);
		echo '</pre>';

		if ($exit)
		{
			exit;
		}
	}

	/**
	 * Pretty printer
	 *
	 * @param mixed $what
	 * @param bool $exit
	 * @param bool $return
	 * @return void|string
	 */
	public static function pp($what, $exit = true, $return = false)
	{
		header('Content-Type: text/html; charset=utf-8');
		$bt = debug_backtrace();
		$caller = array_shift($bt);

		$result = $caller['file'] . ', line: ' . $caller['line'];

		if (!$return)
		{
			echo $result;
		}

		$result .= '<pre>';

		if (!$return)
		{
			echo '<pre>';
		}

		$result .= print_r($what, $return);

		if (!$return)
		{
			echo '</pre>';
		}

		$result .= '</pre>';

		if ($exit)
		{
			exit;
		}

		return $result;
	}

	/**
	 * Pretty exporter
	 *
	 * @param mixed $what
	 * @param bool $exit
	 * @param bool $return
	 * @return void|string
	 */
	public static function pe($what, $exit = true, $return = false)
	{

		$bt = debug_backtrace();
		$caller = array_shift($bt);

		if ($return)
		{
			return var_export($what, $return);
		}

		echo $caller['file'] . ', line: ' . $caller['line'];
		echo '<pre>';
		var_export($what, $return);
		echo '</pre>';

		if ($exit)
		{
			exit;
		}
	}

	/**
	 * Log something to profiler using print_r output
	 *
	 * @param mixed $what
	 * @return void
	 */
	public static function log_pp($what)
	{
		\Laravel\Log::info(static::pp($what, false, true));
	}

}