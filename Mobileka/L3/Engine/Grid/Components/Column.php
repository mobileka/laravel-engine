<?php namespace Mobileka\L3\Engine\Grid\Components;

use Mobileka\L3\Engine\Laravel\HTML;

class Column extends BaseComponent {
	protected $template = 'engine::grid.column';
	protected $raw = false;

	public function value($lang = '')
	{
		$value = parent::value($lang);
		return $this->raw ? $value : nl2br(HTML::entities($value));
	}
}
