<?php namespace Base;

use Helpers\Arr;

/**
 * @author Armen Markossyan <a.a.markossyan@gmail.com>
 * @version 2.0
 * @todo PHPDoc this class
 */
class Model extends \Aware {

	public static $timestamps = true;
	public static $column_registry = array();
	public $columns;
	public $conditions;

	protected function parseInputData($data = array())
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

		return array($data, $relatedData);
	}

	public function saveData($data = array(), $safe = array())
	{
		$relations = array();

		list($data, $relationData) = $this->parseInputData($data);
		list($safe, $safeRelationData) = $this->parseInputData($safe);

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

		foreach (array_unique($relations) as $relation)
		{
			$model = $this->{$relation};

			if (!$model->save())
			{
				$this->mergeRelationErros($model, $relation);
			}
		}

		$this->fill($data);

		foreach ($safe as $key => $value)
		{
			$this->{$key} = $value;
		}

		$this->save();

		return !(bool)$this->errors->messages;
	}

	public function conditions($conditions = array())
	{
		if (!$this->conditions)
		{
			$this->conditions = \Input::get('filters', array());
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
		$order = \Input::get('order', array());
		if (!is_array($order)) {
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
		//$relations = array_unique(array_merge($relations, array_keys($relation_conditions)));

		$per_page = (is_null($per_page))
			? \Input::get('per_page', \Config::get('application.objects_per_page', 25))
			: $per_page;

		$offset = $per_page * (\Input::get('page', 1) - 1);

		$cb = \UrlConditionBuilder::make($query, $this, $relations, $conditions, $relation_conditions);

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

			// $total = $results->count();
			$total = $results->end()->distinct()->count($this->table() . '.id');

			$results = $results->order_by($order_by)
				->group_by($group_by)
				->take($per_page)
				->skip($offset)
				->get($this->table() . '.id');
		}
		catch(\Laravel\Database\Exception $e)
		{
			\Helpers\Debug::log_pp($e->getMessage());
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

		$query = \UrlConditionBuilder::make($query, $this)->get();

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
		$this->errors->messages[$field][] = $message;
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

	public static function getTableName()
	{
		return static::$table ?: strtolower(\Str::plural(class_basename(get_called_class())));
	}

	public function __call($name, $args)
	{
		if ($name == 'links')
		{
			return;
		}

		return parent::__call($name, $args);
	}

	public function __get($name)
	{
		if (in_array($name, array('rules', 'accessible', 'table', 'hidden')))
		{
			return static::$$name;
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