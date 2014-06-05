<?php namespace Mobileka\L3\Engine\Grid\Filters;

use Mobileka\L3\Engine\Laravel\Lang;
use	Mobileka\L3\Engine\Laravel\Helpers\Arr;

class Dropdown extends BaseComponent {

	protected $template = 'engine::grid.filters.dropdown';

	public function value($lang = '')
	{
		return Arr::searchRecursively($this->filters, 'where', $this->name);
	}

	public function options($options, $defaultValue = true)
	{
		if ($this->translate)
		{
			foreach ($options as $key => $option)
			{
				$options[$key] = Lang::findLine($this->languageFile, $option);
			}
		}

		if ($defaultValue)
		{
			$options = array(null => Lang::findLine($this->languageFile, 'not_selected')) + $options;
		}

		$this->options = $options;

		return $this;
	}

}
