<?php namespace Mobileka\L3\Engine\Grid\Filters;

use \Helpers\Arr;

class ContainsFilter extends BaseComponent {

	protected $template = 'crud::grid.filters.contains';

	public function value()
	{
		return Arr::searchRecursively($this->filters, 'contains', $this->name);
	}

}