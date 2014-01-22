<?php namespace Mobileka\L3\Engine\Grid\Filters;

class Date extends DateRangeFilter {

	protected $template = 'crud::grid.filters.date';

	public function value()
	{
		return substr($this->fromValue(), 0, 10);
	}

}