<?php namespace Mobileka\L3\Engine\Laravel;

class Validator extends \Laravel\Validator {

	/**
	 * Replace all error message place-holders with actual values.
	 *
	 * @param  string  $message
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @param  array   $parameters
	 * @return string
	 */
	protected function replace($message, $attribute, $rule, $parameters)
	{
		$message = str_replace(
			':attribute',
			\Str::wrap(
				\Lang::translateIfPossible(
					'default.labels',
					str_replace(' ', '_', $this->attribute($attribute))
				),
				'"'
			),
			$message
		);

		if (method_exists($this, $replacer = 'replace_'.$rule))
		{
			$message = $this->$replacer($message, $attribute, $rule, $parameters);
		}

		return $message;
	}

}