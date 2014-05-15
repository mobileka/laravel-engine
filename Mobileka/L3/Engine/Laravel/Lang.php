<?php namespace Mobileka\L3\Engine\Laravel;

use Mobileka\L3\Engine\Laravel\Helpers\Arr;
use Mobileka\L3\Engine\Laravel\Base\Controller;

class Lang extends \Laravel\Lang {

	/**
	 * If a Request is in a bundle, prepens BUNDLE_NAME::
	 * to a language file key
	 *
	 * @param string $file
	 * @param string $word
	 * @return string
	 */
	public static function key($file, $word)
	{
		$file = $file . '.' . $word;
		$route = Controller::$route;

		// Check if $file already starts with bundle name, to prevent prepending
		// bundle name twice
		if (($p = strpos($file, '::')) !== false)
		{
			if (array_key_exists(substr($file, 0, $p), \Bundle::$bundles))
			{
				return $file;
			}
		}
		if ($bundle = Arr::getItem($route, 'bundle'))
		{
			return $key = $bundle . '::' . $file;
		}

		return $file;
	}

	/**
	 * Tries to find a translation of a $word in a current bundle's translation $file
	 * If fails, tries to find the same thing in the "application.$file"
	 * If fails again, looks up in the "application.$file" replacing everything in $file before the first dot with "default"
	 *
	 * @param string $file
	 * @param string $word
	 * @param array $replacements
	 * @param null|string $language
	 * @return string
	 */
	public static function findLine($file, $word, $replacements = array(), $language = null)
	{
		$key = static::key($file, $word);

		$translation = static::line($key, $replacements, $language)->get();

		if ($key !== $translation)
		{
			return $translation;
		}

		$key = $file . '.' . $word;
		$translation = static::line($key, $replacements, $language)->get();

		if ($key !== $translation or $file == 'default')
		{
			return $translation;
		}

		$key = 'default' . substr($key, strpos($key, '.'));

		return static::line($key, $replacements, $language)->get();
	}

	/**
	 * Tries to find a translation of a $word in a current bundle's translation $file
	 * If fails, tries to find the same thing in the "application" folder
	 * If fails again, looks up in the "application.$file" replacing everything in $file before the first dot with "default"
	 * Lastly, returns an original $word (without appending a path to a language file) if the search was unsuccessful
	 *
	 * @param string $file
	 * @param string $word
	 * @param array $replacements
	 * @param null|string $language
	 * @return string
	 */
	public static function translateIfPossible($file, $word, $replacements = array(), $language = null)
	{
		$translation = static::findLine($file, $word, $replacements, $language);
		$key = $file . '.' . $word;
		$key = 'default' . substr($key, strpos($key, '.'));

		return ($key !== $translation)
			? $translation
			: $word
		;
	}

}
