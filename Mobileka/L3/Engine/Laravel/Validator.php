<?php namespace Mobileka\L3\Engine\Laravel;

class Validator extends \Laravel\Validator {

	protected $languageFile = 'default.labels';

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
			Str::wrap(
				Lang::translateIfPossible(
					$this->languageFile,
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

	public function replace_length($message, $attribute, $rule, $parameters)
	{
		return str_replace(':length', $parameters[0], $message);
	}

	public function __call($method, $args)
	{
		if ($method === 'languageFile')
		{
			$this->languageFile = $args[0];
			return $this;
		}

		return parent::__call($method, $args);
	}

}
