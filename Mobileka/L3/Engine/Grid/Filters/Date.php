<?php namespace Mobileka\L3\Engine\Grid\Filters;

class Date extends DateRange {

	protected $template = 'engine::grid.filters.date';

	public function value($lang = '')
	{
		return substr($this->fromValue(), 0, 10);
	}

}
