<?php namespace Mobileka\L3\Engine\Grid\Filters;

use Mobileka\L3\Engine\Laravel\Helpers\Arr;

class StartsWith extends BaseComponent {

	protected $template = 'engine::grid.filters.starts_with';

	public function value($lang = '')
	{
		return Arr::searchRecursively($this->filters, 'starts_with', $this->name);
	}

}
