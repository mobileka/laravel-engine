<?php namespace Mobileka\L3\Engine\Laravel;

class SphinxConditionBuilder {

	public static $registry;

	public $query;
	public $filters = array();

	public static function instance($query, $filters = array(), $index = '*')
	{
		if (!static::$registry)
		{
			static::$registry = static::make($query, $filters, $index);
		}

		return static::$registry;
	}

	public static function make($query, $filters = array(), $orderBy = array(), $page = 1, $limit = 25)
	{
		$self = new static;
		$self->query = \Sphinx::search($query);

		foreach ($filters as $filter => $value)
		{
			if (method_exists($self, $filter))
			{
				$self->{$filter}($self->query, $value);
			}
		}

		if ($page)
		{
			$offset = ($page - 1) * $limit;
			$self->query->limit($limit, $offset);
		}

		return static::$registry = $self->query;
	}

	/**
	 * in_array(x) (Is contained in an array, WHERE IN() condition)
	 *
	 * @param \Sphinx $sphinx
	 * @param array $filters
	 * @return SphinxConditionBuilder
	 */
	public function in($sphinx, $filters)
	{
		foreach ($filters as $attribute => $values)
		{
			$attribute = str_replace('.', '_', $attribute);
			$sphinx->filter($attribute, $values);
		}

		return $sphinx;
	}

	/**
	 * !in_array(x) (Is not contained in an array, WHERE NOT IN() condition)
	 *
	 * @param \Sphinx $sphinx
	 * @param array $filters
	 * @return SphinxConditionBuilder
	 */
	public function not_in($sphinx, $filters)
	{
		foreach ($filters as $attribute => $values)
		{
			$attribute = str_replace('.', '_', $attribute);
			$sphinx->filter($attribute, $values, true);
		}

		return $sphinx;
	}

	/**
	 * >= x (More than)
	 *
	 * @param \Sphinx $sphinx
	 * @param array $filters
	 * @return SphinxConditionBuilder
	 */
	public function from($sphinx, $filters)
	{
		foreach ($filters as $attribute => $min)
		{
			$attribute = str_replace('.', '_', $attribute);
			$sphinx->range($attribute, $min, 0);
		}

		return $sphinx;
	}

	/**
	 * <= x (Less than)
	 *
	 * @param \Sphinx $sphinx
	 * @param array $filters
	 * @return SphinxConditionBuilder
	 */
	public function to($sphinx, $filters)
	{
		foreach ($filters as $attribute => $max)
		{
			$attribute = str_replace('.', '_', $attribute);
			$sphinx->range($attribute, 0, $max);
		}

		return $sphinx;
	}

	/**
	 * = x (equals to)
	 *
	 * @param \Sphinx $sphinx
	 * @param array $filters
	 * @return SphinxConditionBuilder
	 */
	public function where($sphinx, $filters)
	{
		return $this->in($sphinx, $filters);
	}

	/**
	 * != x (is not equal to)
	 *
	 * @param \Sphinx $sphinx
	 * @param array $filters
	 * @return SphinxConditionBuilder
	 */
	public function not($sphinx, $filters)
	{
		return $this->not_in($sphinx, $filters);
	}

	public function take($sphinx, $take = 0)
	{
		return $this;
	}

	public function skip($sphinx, $skip = 0)
	{
		return $this;
	}
}