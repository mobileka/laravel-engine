<?php namespace Mobileka\Crud\Grid\Filters;

use \Helpers\Arr;

class TextFilter extends BaseComponent {

	protected $template = 'crud::grid.filters.text';

	public function value()
	{
		return Arr::searchRecursively($this->filters, 'where', $this->name);
	}

}