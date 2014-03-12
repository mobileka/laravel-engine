<?php namespace Mobileka\L3\Engine\Laravel\Base;

use Laravel\IoC,
	Mobileka\L3\Engine\Laravel\Helpers\Arr,
	Mobileka\L3\Engine\Laravel\Helpers\Debug,
	Mobileka\L3\Engine\Laravel\Str,
	Mobileka\L3\Engine\Laravel\UrlConditionBuilder,
	Input,
	File;

/**
 * @author Armen Markossyan <a.a.markossyan@gmail.com>
 * @version 2.0
 * @todo PHPDoc and refactor this class
 */
class Model extends \Mobileka\L3\Engine\Base\Laramodel {

	public static $timestamps = true;
	public static $column_registry = array();
	public static $translatable = array();

	public static $data = array(
		'data' => array(),
		'safe' => array(),
		'relatedData' => array(),
		'safeRelationData' => array(),
		'translations' => array(),
		'safeTranslations' => array()
	);

	public $columns;
	public $i18n;
	public $conditions;
	public static $viewRoute = '';

	/**
	 * List of polymorphic relations that should be saved differently from normal
	 * properties. The key is the name of the relationship, the value is
	 * an array specifying the field name and the value it must store.
	 *
	 * Example:
	 *
	 * 		array(
	 * 			'countries' => array(
	 * 				'polymorphic_id'    => 'item_id',
	 * 				'polymorphic_field' => 'item_type',
	 * 				'polymorphic_value' => 'news',
	 * 			),
	 * 		)
	 *
	 * @var array
	 */
	protected static $polymorphicRelations = array();
	public static $imageFields = array();

	public static function getTableName()
	{
		return static::$table ?: strtolower(Str::plural(class_basename(get_called_class())));
	}

	public function __construct($attributes = array(), $exists = false)
	{
		$this->i18n = IoC::resolve('i18n');
		return parent::__construct($attributes, $exists);
	}

	protected function parseInputData($data = array(), $safe = false)
	{
		$relatedData = array();

		foreach ($data as $key => $value)
		{
			if (strpos($key, '__') !== false)
			{
				list($relation, $attribute) = explode('__', $key);

				if (isset($relatedData[$relation]))
				{
					$relatedData[$relation][$attribute] = $value;
				}
				else
				{
					$relatedData[$relation] = array($attribute => $value);
				}

				unset($data[$key]);
				continue;
			}
		}

		$translations = Arr::getItem($data, 'localized', array());

		if ($this->exists and !$safe)
		{
			foreach ($this->i18n->getTranslationsByModel($this) as $t)
			{
				if (isset($translations[$t->lang]) and isset($translations[$t->lang][$t->field]))
				{
					continue;
				}

				$translations[$t->lang][$t->field] = $t->value;
			}
		}

		unset($data['localized']);

		return array($data, $relatedData, $translations);
	}

	/**
	 * Get an array with the values of a given column.
	 *
	 * @param  string  $column
	 * @param  string  $key
	 * @return array
	 */
	public static function lists($column, $key = null)
	{
		$result = array();
		$models = static::all();

		foreach ($models as $model)
		{
			$result[$model->{$key}] = $model->{$column};
		}

		return $result;
	}

	public function saveData($data = array(), $safe = array())
	{
		$relations = array();

		list($data, $relationData, $translations) = $this->parseInputData($data);
		list($safe, $safeRelationData, $safeTranslations) = $this->parseInputData($safe, true);

		static::$data = array(
			'data' => $data,
			'safe' => $safe,

			'relationData' => $relationData,
			'safeRelationData' => $safeRelationData,

			'translations' => $translations,
			'safeTranslations' => $safeTranslations
		);

		foreach ($relationData as $relation => $data)
		{
			$relations[] = $relation;
			$this->{$relation}()->fill($data);
		}

		foreach ($safeRelationData as $relation => $data)
		{
			$relations[] = $relation;
			$key = key($data);
			$value = value($data);

			$this->{$relation}()->{$key} = $value;
		}

		try
		{
			\DB::connection()->pdo->beginTransaction();

			foreach (array_unique($relations) as $relation)
			{
				$model = $this->{$relation};

				if (!$model->save())
				{
					$this->mergeRelationErros($model, $relation);
				}
			}

			/* POLYMORPH */
			$polymorphicData = array();

			if (static::$polymorphicRelations)
			{
				foreach (static::$polymorphicRelations as $relation => $relationParams)
				{
					if (isset($data[$relation]) && $data[$relation])
					{
						$polymorphicData[$relation] = $data[$relation];
					}

					unset($data[$relation]);
				}
			}
			/* POLYMORPH */

			$this->fill($data);

			foreach ($safe as $key => $value)
			{
				$this->{$key} = $value;
			}

			$this->save();

			/* POLYMORPH */
			$this->savePolymorphicData($polymorphicData);

			if (!$this->beforeLocalizedSave())
			{
				throw new \PDOException('beforeLocalizedSave() returned false', 12);
			}

			$this->saveLocalizedData(static::$data['translations'], static::$data['safeTranslations']);

			if (!$this->afterLocalizedSave())
			{
				throw new \PDOException('afterLocalizedSave() returned false', 12);
			}

			if ((bool)$this->errors->messages)
			{
				throw new \PDOException('There are '. count($this->errors->messages) . ' validation errors detected', 12);
			}

			\DB::connection()->pdo->commit();
		}
		catch(\PDOException $e)
		{
			if (!in_array($e->getCode(),array('42S22')))
			{
				\Log::info("\n\n\n###################################################################################################n");
				Debug::log_pp("Exception code: " . $e->getCode() . "\n", false);
				Debug::log_pp($e->getMessage(), false);
				\Log::info("\n###################################################################################################n\n\n");
				return false;
			}

			throw $e;
		}

		return true;
	}

	public function beforeLocalizedSave()
	{
		return true;
	}

	public function saveLocalizedData($translations, $safeTranslations)
	{
		foreach ($translations as $lang => $fields)
		{
			$validation = \Validator::make($fields, Arr::getItem(static::$translatable, 'rules', array()));

			if ($validation->fails())
			{
				foreach ($validation->errors->messages as $field => $message)
				{
					$this->addError('localized: ' . $field . '_' . $lang, $message);
				}

				continue;
			}

			foreach ($fields as $field => $value)
			{
				try
				{
					$this->saveLocalizedField($field, $value, $lang);
				}
				catch(\Exception $e)
				{
					$this->addError('localized: ' . $field . '_' . $lang, $e->getMessage());
				}
			}
		}

		foreach ($safeTranslations as $lang => $fields)
		{
			foreach ($fields as $field => $value)
			{
				try
				{
					$this->saveLocalizedField($field, $value, $lang);
				}
				catch(\Exception $e)
				{
					$this->addError('localized: ' . $field . '_' . $lang, $e->getMessage());
				}
			}
		}
	}

	public function afterLocalizedSave()
	{
		return true;
	}

	public function setLocalized($field, $value, $lang)
	{
		if (!Arr::getItem(static::$data['translations'], $lang))
		{
			static::$data['translations'][$lang] = array();
		}

		static::$data['translations'][$lang][$field] = $value;
	}

	public function localized($field, $lang = '')
	{
		$result = Arr::searchRecursively(static::$data, 'translations', $lang, array());
		$hasField = array_key_exists($field, $result);
		if (!$hasField && $this->exists)
		{
			$result = $this->i18n->getByModel($field, $this, $lang);
		}
		else
		{
			$result = $hasField ? $result[$field] : null;
		}

		return $result;
	}

	public function saveLocalizedField($field, $value, $lang = '')
	{
		if (!$this->id)
		{
			throw new \Exception("Trying to localize a field \"$field\" of an unsaved model " .get_class($this));
		}

		return $this->i18n->saveByModel($field, $value, $this, $lang);
	}

	/* POLYMORPH */
	protected function savePolymorphicData($polymorphicData)
	{
		foreach (static::$polymorphicRelations as $relation => $relationParams)
		{
			$relationObject = $this->$relation();

			$this->$relation()->delete();

			if (isset($polymorphicData[$relation]) and $polymorphicData[$relation])
			{
				$relatedValues = array(
					$relationParams['polymorphic_id']    => $this->id,
					$relationParams['polymorphic_field'] => $relationParams['polymorphic_value'],
				);

				foreach ($polymorphicData[$relation] as $relatedId)
				{
					$this->$relation()->attach($relatedId, $relatedValues);
				}

			}
		}
	}


	public function conditions($conditions = array())
	{
		if (!$this->conditions)
		{
			$this->conditions = Input::get('filters', array());
		}

		foreach ($this->conditions as $filter => $fields)
		{
			if (isset($conditions[$filter]))
			{
				foreach ($fields as $field => $value)
				{
					if (!isset($conditions[$field]))
					{
						$conditions[$filter][$field] = $value;
					}
				}
			}
		}

		return array_merge($this->conditions, $conditions);
	}

	public function _order($order_by = array())
	{
		$order = Input::get('order', array());

		if (!is_array($order))
		{
			$order = array($order);
		}

		return array_merge($order_by, $order);
	}

	public function buildQuery(
		$relations = array(),
		$conditions = array(),
		$order_by = array(),
		$per_page = null
	)
	{
		$relation_conditions = array();

		if (Arr::getItem($conditions, 'raw'))
		{
			array_forget($conditions, 'raw');
		}
		else
		{
			$conditions = $this->conditions($conditions);
		}

		$order_by = $this->_order($order_by);
		$query = \DB::table($this->table());

		$possible_conditions = array(
			'in',
			'from',
			'to',
			'contains',
			'where',
			'starts_with',
			'ends_with',
			'or_where',
			'not',
			'not_in',
			'group_by'
		);

		foreach ($possible_conditions as $potential_filter)
		{
			$$potential_filter = Arr::getItem($conditions, $potential_filter, array());

			foreach ($$potential_filter as $key => $value)
			{
				if (strpos($key, '.') !== false)
				{
					$tmp = explode('.', $key);
					$fieldName = $tmp[1];

					for ($i = 2, $count = count($tmp); $i < $count; $i++)
					{
						$fieldName .= '_'.$tmp[$i];
					}

					$relation_conditions[$tmp[0]][$potential_filter][$fieldName] = $value;
					unset(${$potential_filter}[$key]);
				}
			}
		}

		/**
		 * @todo: look for bugz here
		 */
		$relations = array_unique(array_merge($relations, array_keys($relation_conditions)));

		$per_page = (is_null($per_page))
			? Input::get('per_page', \Config::get('application.objects_per_page', 25))
			: $per_page;

		$offset = $per_page * (Input::get('page', 1) - 1);

		$cb = UrlConditionBuilder::make($query, $this, $relations, $conditions, $relation_conditions);

		try {
			foreach ($relations as $relation)
			{
				$rels = explode('.', $relation);

				$cb->join($rels)
					->in_related($relation)
					->from_related($relation)
					->to_related($relation)
					->starts_with_related($relation)
					->ends_with_related($relation)
					->contains_related($relation)
					->where_related($relation)
					->or_where_related($relation)
					->not_related($relation)
					->not_in_related($relation);
			}

			$results = $cb->in($in)
				->from($from)
				->to($to)
				->starts_with($starts_with)
				->ends_with($ends_with)
				->contains($contains)
				->where($where)
				->or_where($or_where)
				->not($not)
				->not_in($not_in);

			$total = $results->end()->distinct()->count($this->table() . '.id');

			$results = $results->order_by($order_by)
				->group_by($group_by)
				->take($per_page)
				->skip($offset)
				->get($this->table() . '.id');
		}
		catch(\Laravel\Database\Exception $e)
		{
			Debug::log_pp($e->getMessage());
			$results = array();
		}

		$ids = array_map(
			function($result)
			{
				return $result->id;
			},
			$results
		);

		if (!$ids)
		{
			$this->results = array();
			return $this;
		}

		$appends = array('conditions' => $conditions, 'per_page' => $per_page, 'order' => $order_by);

		$query = $this;

		if ($relations)
		{
			$query = $this->with($relations);
		}

		$query = $query->where_in($this->table() . '.id', $ids);

		if ((int)$per_page === 0)
		{
			$this->results = $query->get();
			return $this;
		}

		$query = UrlConditionBuilder::make($query, $this)->get();

		if ($order_by and $query)
		{
			$results = array();
			$foundIds = array_pluck($query, 'id');

			foreach ($ids as $id)
			{
				$key = array_search($id, $foundIds);

				if ($key !== false)
				{
					$results[] = $query[$key];
				}
			}

			$query = $results;
		}

		return \Paginator::make($query, $total, $per_page)->appends($appends);
	}

	public function setAttr($data, $key)
	{
		if (isset($data[$key]))
		{
			$this->{$key} = $data[$key];
			return true;
		}

		return false;
	}

	/**
	 * Manually attach an error to a current model
	 *
	 * @param string $field - a name of a field to attach an error to
	 * @param string $message - an error text
	 * @return \Laravel\Messages
	 */
	public function addError($field, $message)
	{
		$message = is_array($message) ? $message : array($message);

		foreach ($message as $msg)
		{
			$this->errors->messages[$field][] = $msg;
		}

		return $this->errors;
	}

	/**
	 * Merge errors of several models
	 *
	 * @param array|Eloquent $models
	 * @return \Laravel\Messages
	 */
	public function mergeErrors($models)
	{
		$models = is_array($models) ? $models : array($models);

		foreach ($models as $model)
		{
			$this->errors->messages = array_merge(
				$this->errors->messages,
				$model->errors->messages
			);
		}

		return $this->errors;
	}

	/**
	 * Merge errors of several models according to CRUD convention
	 *
	 * @param array|Eloquent $models
	 * @return \Laravel\Messages
	 */
	public function mergeRelationErros($model, $name)
	{
		foreach ($model->errors->messages as $key => $errors)
		{
			$this->errors->messages[$name . '__' . $key] = $errors;
		}

		return $this->errors;
	}

	public function uploads()
	{
		return $this->has_many(IoC::resolve('Uploader'), 'object_id')
			->where_type($this->table());
	}


	public function __call($name, $args)
	{
		if ($name == 'links')
		{
			return;
		}
		if (\Str::contains($name, '_uploads'))
		{
			$field = str_replace('_uploads', '', $name);

			return $this->uploads()->where_fieldname($field);
		}

		return parent::__call($name, $args);
	}

	public function __get($name)
	{
		if (in_array($name, array('rules', 'accessible', 'table', 'hidden', 'translatable')))
		{
			return static::$$name;
		}

		if (\Str::contains($name, '_uploads'))
		{
			if ($uploads = $this->$name())
			{
				return $uploads->get();
			}
		}

		if (in_array($name, Arr::getItem(static::$translatable, 'fields', array())))
		{
			return $this->localized($name);
		}

		return parent::__get($name);
	}

	public function __set($name, $value)
	{
		if ($name == 'table')
		{
			/**
			 * @todo wtf?
			 */
			$this->table = $value;
		}

		return parent::__set($name, $value);
	}

	public function columns()
	{
		if (!is_null($this->columns))
		{
			return $this->columns;
		}

		if (is_null($this->table))
		{
			$this->table = Str::lower(Str::plural(get_class($this)));
		}

		if (!$result = Arr::getItem(static::$column_registry, $this->table, array()))
		{
			$columns = DB::query('SHOW COLUMNS FROM ' . $this->table);

			foreach ($columns as $column)
			{
				$result[] = $column->field;
			}
		}

		return $this->columns = static::$column_registry[$this->table] = $result;
	}

	public static function getProperty($object, $property, $defaultValue = null)
	{
		return ($object and isset($object, $property)) ? $object->{$property} : $defaultValue;
	}
}
