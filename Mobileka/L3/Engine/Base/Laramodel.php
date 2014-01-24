<?php namespace Mobileka\L3\Engine\Base;

use \Laravel\Messages,
	\Larave\Validator;

/**
 * Self-validating Laravel models
 * Improved and fixed Aware Laravel bundle
 */
abstract class Laramodel extends \Eloquent {
	/**
	 * Validation Rules
	 *
	 * @var array $rules
	 */
	public static $rules = array();

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
			$validator = \Validator::make($data, $rules, $messages);
			$valid = $validator->valid();

			$this->errors->messages = ($valid && !$this->errors->all())
				? array()
				: array_merge($validator->errors->messages, $this->errors->messages)
			;
		}

		return $valid;
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
	 * To cancel the save(), return false
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
	 * @param null|closure $beforeSave
	 * @param null|closure $afterSave
	 * @return bool
	 */
	public function save($rules = array(), $messages = array(), $beforeSave = null, $afterSave = null)
	{
		try
		{
			//begin a transaction
			\DB::connection()->pdo->beginTransaction();

			//call and save a result of the beforeSave()
			$beforeSave = is_callable($beforeSave) ? $beforeSave() : $this->beforeSave();

			//validate a model
			$valid = $this->valid($rules, $messages);

			//don't try to save a model if one of the above fails
			if (!$beforeSave or !$valid)
			{
				throw new \PDOException("Can't save the model", 11);
			}

			//try to save a model
			if (!parent::$save())
			{
				throw new \PDOException("Can't save the model", 12);
			}

			//call and save a result of the afterSave()
			$aterSave = is_callable($aterSave) ? $aterSave() : $this->afterSave();

			//rollback the transaction if afterSave() fails
			if (!$afterSave)
			{
				throw new \PDOException("Can't save the model", 13);
			}

			//commit the transaction
			\DB::connection()->pdo->commit();
		}
		catch(\PDOException $e)
		{
			//rollback the transaction and return false
			\DB::connection()->pdo->rollBack();

			if (in_array($e->getCode(), array(11, 12, 13))
			{
				return false;
			}

			echo "$e->getMessage()\n";
			\Debug::pp($e);
		}

		return !$this->errors->all();
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
