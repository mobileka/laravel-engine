<?php namespace Mobileka\L3\Engine\Grid\Filters;

class Contains extends BaseComponent {

	protected $template = 'engine::grid.filters.contains';

	public function value()
	{
		return \Arr::searchRecursively($this->filters, 'contains', $this->name);
	}

}