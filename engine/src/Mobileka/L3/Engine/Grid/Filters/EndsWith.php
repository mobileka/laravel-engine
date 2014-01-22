<?php namespace Mobileka\L3\Engine\Grid\Filters;

class EndsWith extends BaseComponent {

	protected $template = 'crud::grid.filters.ends_with';

	public function value()
	{
		return \Arr::searchRecursively($this->filters, 'ends_with', $this->name);
	}

}