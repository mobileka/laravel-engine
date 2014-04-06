<?php namespace Mobileka\L3\Engine\Grid\Filters;

use Mobileka\L3\Engine\Laravel\Helpers\Arr;

class EndsWith extends BaseComponent {

	protected $template = 'engine::grid.filters.ends_with';

	public function value($lang = '')
	{
		return Arr::searchRecursively($this->filters, 'ends_with', $this->name);
	}

}
