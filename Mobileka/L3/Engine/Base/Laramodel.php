<?php namespace Mobileka\L3\Engine\Base;

use \Laravel\Messages,
	\Mobileka\L3\Engine\Laravel\Validator;

/**
 * Self-validating Laravel models
 * Improved and fixed Aware Laravel bundle
 */
abstract class Laramodel extends \Laravel\Database\Eloquent\Model {
	/**
	 * Validation Rules
	 *
	 * @var array $rules
	 */
	public static $rules = array();

	/**
	 * In order to make validation error messages friendlier,
	 * Laravel Engine tries to traslate field names.
	 * This property configures the translation file
	 *
	 * @var string $validationLanguageFile
	 */
	public static $validationLanguageFile = 'default.labels';

	/**
	 * Validation Messages
	 *
	 * @var array $messages
	 */
	public static $messages = array();

	/**
	 * Errors
	 *
	 * @var Laravel\Messages $errors
	 */
	public $errors;

	/**
	 * Create new instance
	 *
	 * @param array $attributes
	 * @return void
	 */
	public function __construct($attributes = array(), $exists = false)
	{
		$this->errors = new Messages;
		parent::__construct($attributes, $exists);
	}

	/**
	 * Validate the model
	 *
	 * @param array $rules
	 * @param array $messages
	 * @return bool
	 */
	public function valid($rules = array(), $messages = array())
	{
		$valid = true;

		if ($rules or static::$rules)
		{
			//check whether messages or rules were overided
			$rules = $rules ? : static::$rules;
			$messages = $messages ? : static::$messages;

			// if the model exists, this is an update
			if ($this->exists)
			{
				// and only include dirty fields
				$data = $this->get_dirty();

				// so just validate the fields that are being updated
				$rules = array_intersect_key($rules, $data);
			}
			else
			{
				// otherwise validate everything!
				$data = $this->attributes;
			}

			// construct the validator
			$validator = Validator::make($data, $rules, $messages)->languageFile(static::$validationLanguageFile);
			$valid = $validator->valid();

			$this->errors->messages = ($valid && !$this->errors->all())
				? array()
				: array_merge($validator->errors->messages, $this->errors->messages)
			;
		}

		return $valid;
	}

	/**
	 * This method is being called everytime before the model is being validated
	 * @return bool
	 */
	public function beforeValidation()
	{
		return true;
	}

	/**
	 * This method is being called everytime after the model is being validated
	 * @return bool
	 */
	public function afterValidation()
	{
		return true;
	}

	/**
	 * This method is being called everytime before the model is being saved
	 * To halt the save(), return false
	 * @return bool
	 */
	public function beforeSave()
	{
		return true;
	}

	/**
	 * This method is being called everytime after the model is being saved
	 * @return bool
	 */
	public function afterSave()
	{
		return true;
	}

	/**
	 * Saves the model
	 *
	 * @param array $rules
	 * @param array $messages
	 * @param null|closure $beforeValidation
	 * @param null|closure $afterValidation
	 * @param null|closure $beforeSave
	 * @param null|closure $afterSave
	 * @return bool
	 */
	public function save($rules = array(), $messages = array(), $beforeValidation = null, $afterValidation = null, $beforeSave = null, $afterSave = null)
	{
		try
		{
			$beforeValidation = is_callable($beforeValidation) ? $beforeValidation() : $this->beforeValidation();

			if (!$beforeValidation)
			{
				$this->throwPdoException(8);
			}

			//validate a model
			if (!$this->valid($rules, $messages))
			{
				$this->throwPdoException(9);
			}

			$afterValidation = is_callable($afterValidation) ? $afterValidation() : $this->afterValidation();

			if (!$afterValidation)
			{
				$this->throwPdoException(10);
			}

			//call and save a result of the beforeSave()
			$beforeSave = is_callable($beforeSave) ? $beforeSave() : $this->beforeSave();

			//don't try to save a model if one of the above fails
			if (!$beforeSave)
			{
				$this->throwPdoException(11);
			}

			//try to save a model
			if (!parent::save())
			{
				$this->throwPdoException(12);
			}

			//call and save a result of the afterSave()
			if (is_callable($afterSave))
			{
				$afterSave();
			}
			else
			{
				$this->afterSave();
			}
		}
		catch(\Exception $e)
		{
			if (in_array($e->getCode(), range(8, 13)))
			{
				return false;
			}

			throw $e;
		}

		return !$this->errors->all();
	}

	public function beforeDelete()
	{
		return true;
	}

	public function delete()
	{
		if (!$this->beforeDelete())
		{
			return false;
		}

		$result = parent::delete();

		$this->afterDelete();

		return $result;
	}

	public function afterDelete()
	{
		return true;
	}

	protected function throwPdoException($code = 0, $message = 'Can\'t save model')
	{
		throw new \Exception($message, $code);
	}


	/**
	 * Ignore unchanged attrbutes
	 *
	 * @param string $key
	 * @param string|num|bool|etc $value
	 * @return void
	 */
	public function __set($key, $value)
	{
		if (!array_key_exists($key, $this->attributes) or ($value !== $this->$key))
		{
			return parent::__set($key, $value);
		}
	}
}
