<?php namespace Mobileka\L3\Engine\Laravel;

/**
 * @author Armen Markossyan <a.a.markossyan@gmail.com>
 * @version 1.0
 */
class Loader extends BaseClass {
	/**
	 * Recursively load (require) files from a given $path
	 *
	 * @param string $path
	 * @return void
	 */
	public static function requireDirectory($path)
	{
		$directory = new RecursiveDirectoryIterator($path);
		$iterator = new RecursiveIteratorIterator($directory);
		$files = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

		foreach ($files as $file)
		{
			require $file[0];
		}
	}
}