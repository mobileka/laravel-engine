<?php namespace Mobileka\L3\Engine\Grid\Components;

class Boolean extends BaseComponent {

	protected $template = 'engine::grid.column';

	public function value($lang = '')
	{
		if ((bool)parent::value())
		{
			return 'Да';
		}
		return 'Нет';
	}
}
