<?php
use Illuminate\Support\Str as LaraStr;

function contains($haystack, $needles)
{
	return Str::contains($haystack, $needles);
}

function capitalize($string)
{
	return Str::title($string);
}

/**
 * Wrap a string with a given $wrapper
 *
 * @param string $str
 * @param string $wrapper
 * @return string
 */
function wrap_string($str, $wrapper)
{
	return Str::wrap($str, $wrapper);
}

/**
 * Transliterate a string
 *
 * @param string $str
 * @return string
 */
function transliterate($str)
{
	return Str::transliterate($str);
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
function limitWords($str, $limit = 100, $end_char = '&#8230;')
{
	return Str::limitWords($str, $limit, $end_char);
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
function limitCharacters($str, $len = 500)
{
	return Str::limitCharacters($str, $len);
}