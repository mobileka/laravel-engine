<?php namespace Mobileka\L3\Engine\Laravel\Helpers;

/**
 * @author Armen Markossyan <a.a.markossyan@gmail.com>
 * @version 1.0
 */
class Arr {

	/**
	 * Determine if two arrays have at least a single matching element
	 *
	 * @param array $searchForUs
	 * @param array $searchHere
	 * @param bool $strict
	 * @return bool
	 */
	public static function haveIntersections(Array $searchForUs, Array $searchHere, $strict = false)
	{
		foreach ($searchForUs as $find_me)
		{
			if (in_array($find_me, $searchHere, $strict))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Implode an array recursively
	 *
	 * @param array $array
	 * @param string $glue
	 * @return string
	 */
	public static function implodeRecursively(Array $array, $glue)
	{
		$result = '';

		foreach ($array as $item)
		{
			$result .= is_array($item) ? static::implodeRecursively($item, $glue) . $glue : $item . $glue;
		}

		return substr($result, 0, 0 - strlen($glue));
	}

	/**
	 * Return the first truthy element of an array.
	 * If none of them is truthy, return a defaultValue.
	 *
	 * @param array $value
	 * @param mixed $defaultValue
	 * @return array
	 */
	public static function find(Array $values, $defaultValue = null)
	{
		foreach ($values as $value)
		{
			if (Misc::truthyValue($value, false))
			{
				return $value;
			}
		}

		return $defaultValue;
	}

	/**
	 * Get an array of keys matching a regular expression
	 *
	 * @param string $pattern
	 * @param array $array
	 * @param mixed $flags
	 * @return array
	 */
	public static function pregGrepKeys($pattern, $array, $flags = 0)
	{
		return preg_grep($pattern, array_keys($array), $flags);
	}

	/**
	 * Get an array of elements (key => value pairs) by a key matching a regular expression.
	 * Return a provided defaultValue unless such key exists
	 *
	 * @param array $array
	 * @param string $pattern
	 * @param mixed $defaultResult
	 * @return mixed
	 */
	public static function pregAssoc($array, $pattern, $defaultResult = array())
	{
		if ($keys = array_flip(static::pregGrepKeys($pattern, $array)))
		{
			if ($result = array_intersect_key($array, $keys))
			{
				return $result;
			}
		}

		return $defaultResult;
	}

	/**
	 * Get values from an array by a key matching a regular expression if it exsists.
	 * Otherwise, return a $default_value
	 *
	 * @param array $array
	 * @param string $pattern
	 * @param mixed $defaultResult
	 * @return mixed
	 */
	public static function pregValues($array, $pattern, $defaultResult = array())
	{
		if ($result = static::pregAssoc($array, $pattern, false))
		{
			return array_values($result);
		}

		return $defaultResult;
	}

	/**
	 * Get a value from an array by key in case when it exsists.
	 * Otherwise, return a $default_value
	 *
	 * @param array $array
	 * @param string $key
	 * @param mixed $defaultResult
	 * @return mixed
	 */
	public static function getItem($array, $key, $defaultResult = false)
	{
		return (is_array($array) and isset($array[$key])) ? $array[$key] : $defaultResult;
	}

	/**
	 * Search an array recursively for a key and a subkey.
	 * If both of them exist and a $subkey is a direct child of a $key
	 * return a value stored in a $subkey.
	 * Otherwise, return a provided default value
	 *
	 * @param array $array
	 * @param string $key
	 * @param string $subkey
	 * @param mixed $defaultResult
	 * @return mixed
	 */
	public static function searchRecursively($array, $key, $subkey, $defaultResult = false)
	{
		if (!is_array($array))
		{
			return $defaultResult;
		}

		foreach ($array as $k => $v)
		{
			if ($k == $key and is_array($v) and isset($v[$subkey]))
			{
				return $v[$subkey];
			}

			$data = static::searchRecursively($v, $key, $subkey);

			if ($data != false)
			{
				return $data;
			}
		}

		return $defaultResult;
	}

	/**
	 * Pluck an array of values from an array.
	 * Ignore If $key doesn't exist.
	 *
	 * @param  array   $array
	 * @param  string  $key
	 * @return array
	 */
	public static function permissivePluck($array, $key)
	{
		return array_map(function($element) use ($key)
		{
			return is_object($element) ? @$element->$key : @$element[$key];
		}, $array);
	}

	/**
	 * Exclude specified keys form a given array
	 *
	 * @param array $array
	 * @param array $keys
	 * @return array
	 */
	public static function except($array, $keys)
	{
		return ($keys) ? array_except($array, $keys) : $array;
	}

	/**
	 * Get a subset of items from a given array
	 *
	 * @param array $array
	 * @param array $keys
	 * @return array
	 */
	public static function only($array, $keys)
	{
		return ($keys) ? array_only($array, $keys) : $array;
	}

	/**
	 * Get a subset of values from a given array
	 *
	 * @param array $array
	 * @param array $values
	 * @return array
	 */
	public static function onlyValues($array, $values)
	{
		return ($values) ? array_flip(array_only(array_flip($array), $values)) : $array;
	}

	/**
	 * Exclude specified values form a given array
	 *
	 * @param array $array
	 * @param array $values
	 * @return array
	 */
	public static function exceptValues($array, $values)
	{
		return ($values) ? array_flip(array_except(array_flip($array), $values)) : $array;
	}

	/**
	 * Sort an array by another array
	 *
	 * @param array $array
	 * @param array $orderBy
	 * @return array
	 */
	public static function sortByArray(array $array, array $orderBy)
	{
		if ($orderBy)
		{
			$array = array_flip($array);
			$ordered = array();

			foreach ($orderBy as $key)
			{
				if (array_key_exists($key, $array))
				{
					$ordered[$key] = $array[$key];
					unset($array[$key]);
				}
			}

			$array = array_flip($ordered + $array);
		}

		return $array;
	}

	/**
	 * Merges values of two arrays (removes keys)
	 *
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 */
	public static function mergeValues($array1, $array2)
	{
		$result = array();

		foreach ($array1 as $key => $value)
		{
			$result[] = $value;
		}

		foreach ($array2 as $key => $value)
		{
			$result[] = $value;
		}

		return $result;
	}


	/**
	 * Exlude an item from an array if its value meets a rule
	 *
	 * @todo add possibility to pass a closure as a rule
	 * @param array $array
	 * @param mixed $rule
	 * @param bool $strict
	 * @return array
	 */
	public static function excludeByRule(Array $array, $rule = false, $strict = false)
	{
		foreach ($array as $key => $value)
		{
			if ($strict and $value === $rule)
			{
				unset($array[$key]);
			}
			elseif ($value == $rule)
			{
				unset($array[$key]);
			}
		}

		return $array;
	}

	/**
	 * Prepares an array of models to be used in a select box
	 *
	 * @param Array $models
	 * @param string $key
	 * @param string $value
	 * @return array
	 */
	public static function modelsToSelect(Array $models, $key = 'id', $value = 'name')
	{
		$result = array();

		foreach ($models as $model)
		{
			$result[$model->{$key}] = $model->{$value};
		}

		return $result;
	}

	public static function selfCombine(array $array)
	{
		$array = array_values($array);
		return array_combine($array, $array);
	}

	/**
	 * Converts each element of array to integer
	 *
	 * @param array $array
	 * @return array
	 */
	public static function toInt(array $array)
	{
		return array_map(function($element)
			{ 
				return (int)$element;
			}, 
			$array
		);
	} 
}