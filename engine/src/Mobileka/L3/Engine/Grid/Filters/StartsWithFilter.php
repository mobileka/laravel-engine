<?php namespace Mobileka\L3\Engine\Grid\Filters;

use \Helpers\Arr;

class StartsWithFilter extends BaseComponent {

	protected $template = 'crud::grid.filters.starts_with';

	public function value()
	{
		return Arr::searchRecursively($this->filters, 'starts_with', $this->name);
	}

}