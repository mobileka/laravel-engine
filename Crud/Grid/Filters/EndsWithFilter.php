<?php namespace Mobileka\Crud\Grid\Filters;

use \Helpers\Arr;

class EndsWithFilter extends BaseComponent {

	protected $template = 'crud::grid.filters.ends_with';

	public function value()
	{
		return Arr::searchRecursively($this->filters, 'ends_with', $this->name);
	}

}