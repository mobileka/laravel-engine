<?php namespace Mobileka\L3\Engine\Grid\Components;

class Link extends BaseComponent {

	protected $template = 'crud::grid.link';
	protected $params   = array();
	protected $label    = '';
	protected $route    = '';

	public function label($value, $translate = false)
	{
		$this->label = $translate ? \Lang::findLine($translate, 'grid.' . $value) : $value;
		return $this;
	}

	public function __get($name)
	{
		if ($name === 'params')
		{
			foreach ($this->params as $key => $param)
			{
				if (\Str::contains($param, ':'))
				{
					$param = ltrim ($param, ':');
					$params[$key] = $this->row->{$param};
				}
			}

			return $params;
		}

		if ($name === 'label')
		{
			$row = $this->row;

			$label = preg_replace_callback(
				'/:\w+/',
				function($matches) use($row)
				{
					$property = ltrim ($matches[0], ':');
					return $row->{$property};
				},
				$this->label
			);

			return $label;
		}

		return parent::__get($name);
	}

}