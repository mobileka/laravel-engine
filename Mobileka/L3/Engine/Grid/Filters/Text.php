<?php namespace Mobileka\L3\Engine\Grid\Filters;

class Text extends BaseComponent {

	protected $template = 'engine::grid.filters.text';

	public function value($lang = '')
	{
		return \Arr::searchRecursively($this->filters, 'where', $this->name);
	}

}
