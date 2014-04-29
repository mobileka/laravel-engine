<?php namespace Mobileka\L3\Engine\Laravel;

class Str extends \Laravel\Str {

	/**
	 * Convert a camel-cased string to a snake case
	 *
	 * @param string $str
	 * @return string
	 */
	public static function camelToSnake($str)
	{
		return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $str));
	}

	/**
	 * Convert a snake-cased string to a camel case
	 *
	 * @param string $str
	 * @return string
	 */
	public static function snakeToCamel($str)
	{
		return preg_replace_callback(
			'/_([a-z])/',
			function($s) use($str)
			{
				return strtoupper($s[1]);
			},
			$str
		);
	}

	/**
	 * Wrap a string with a given $wrapper
	 *
	 * @param string $str
	 * @param string $wrapper
	 * @return string
	 */
	public static function wrap($str, $wrapper)
	{
		return $wrapper . $str . $wrapper;
	}

	/**
	 * Finds out whether a $haystack contains a $needle
	 *
	 * @param string $haystack
	 * @param string $needle
	 * @return bool
	 */
	public static function contains($haystack, $needle)
	{
		return strpos($haystack, $needle) !== false;
	}

	/**
	 * Transliterate a string
	 *
	 * @param string $str
	 * @return string
	 */
	public static function transliterate($str)
	{
		$str = strtr(
			$str,
			array(
				'А' => 'A', 'а' => 'a',
				'Б' => 'B', 'б' => 'b',
				'В' => 'V', 'в' => 'v',
				'Г' => 'G', 'г' => 'g',
				'Д' => 'D', 'д' => 'd',
				'Е' => 'E', 'е' => 'e',
				'Ё' => 'E', 'ё' => 'e',
				'Ж' => 'Zh', 'ж' => 'zh',
				'З' => 'Z', 'з' => 'z',
				'И' => 'I', 'и' => 'i',
				'Й' => 'I', 'й' => 'i',
				'К' => 'K', 'к' => 'k',
				'Л' => 'L', 'л' => 'l',
				'М' => 'm', 'м' => 'm',
				'Н' => 'N', 'н' => 'n',
				'О' => 'o', 'о' => 'o',
				'П' => 'P', 'п' => 'p',
				'Р' => 'R', 'р' => 'r',
				'С' => 'S', 'с' => 's',
				'Т' => 'T', 'т' => 't',
				'У' => 'U', 'у' => 'u',
				'Ф' => 'F', 'ф' => 'f',
				'Х' => 'H', 'х' => 'h',
				'Ц' => 'Ts', 'ц' => 'ts',
				'Ч' => 'Ch', 'ч' => 'ch',
				'Ш' => 'Sh', 'ш' => 'sh',
				'Щ' => 'Shch', 'щ' => 'shch',
				'Ъ' => '', 'ъ' => '',
				'Ы' => 'Y', 'ы' => 'y',
				'Ь' => '', 'ь' => '',
				'Э' => 'E', 'э' => 'e',
				'Ю' => 'Yu', 'ю' => 'yu',
				'Я' => 'Ya', 'я' => 'ya',
			)
		);

		return static::slug($str);
	}

	/**
	 * Codeigniter Word Limiter
	 *
	 * Limits a string to X number of words.
	 *
	 * @param	string
	 * @param	int
	 * @param	string	the end character. Usually an ellipsis
	 * @return	string
	 */
	public static function limitWords($str, $limit = 100, $end_char = '&#8230;')
	{
		if (trim($str) === '')
		{
			return $str;
		}

		preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);

		if (strlen($str) === strlen($matches[0]))
		{
			$end_char = '';
		}

		return rtrim($matches[0]).$end_char;
	}

	/**
	 * Character Limiter
	 *
	 * Limits the string based on the character count.
	 *
	 * @param	string
	 * @param	int
	 * @return	string
	 */
	public static function limitCharacters($str, $len = 500)
	{
		if (mb_strlen($str) > $len)
		{
			$str = mb_substr($str, 0, ($len - 3)) . '...';
		}

		return $str;
	}
}