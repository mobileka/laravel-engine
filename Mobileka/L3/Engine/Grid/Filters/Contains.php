<?php namespace Mobileka\L3\Engine\Grid\Filters;

use Mobileka\L3\Engine\Laravel\Helpers\Arr;

class Contains extends BaseComponent {

	protected $template = 'engine::grid.filters.contains';

	public function value($lang = '')
	{
		return Arr::searchRecursively($this->filters, 'contains', $this->name);
	}

}
