<?php namespace Mobileka\L3\Engine\Form\Components;

class Hidden extends BaseComponent {

	protected $template = 'engine::form.hidden';

	public function isHidden()
	{
		return true;
	}

}
