<?php namespace Mobileka\Crud\Grid\Filters;

use \Helpers\Arr;

class DateFilter extends DateRangeFilter {

	protected $template = 'crud::grid.filters.date';

	public function value()
	{
		return substr($this->fromValue(), 0, 10);
	}

}