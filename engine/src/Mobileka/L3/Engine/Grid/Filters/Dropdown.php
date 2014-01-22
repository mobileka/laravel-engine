<?php namespace Mobileka\L3\Engine\Grid\Filters;

class Dropdown extends BaseComponent {

	protected $template = 'crud::grid.filters.dropdown';

	public function value()
	{
		return \Arr::searchRecursively($this->filters, 'where', $this->name);
	}

	public function options($options, $defaultValue = true)
	{
		if ($defaultValue)
		{
			$options = array(null => \Lang::findLine($this->languageFile, 'not_selected')) + $options;
		}

		$this->options = $options;

		return $this;
	}

}