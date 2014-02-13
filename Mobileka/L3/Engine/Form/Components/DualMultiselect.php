<?php namespace Mobileka\L3\Engine\Form\Components;

class DualMultiselect extends Dropdown {

	protected $template         = 'engine::form.dual_multiselect';

	protected $requiredAttributes = array(
		'class'                 => 'multiselect',
		'multiple'              => 'multiple',
		'data-selectableheader' => 'Первый заголовок',
		'data-selectionheader'  => 'Второй заголовок',
	);

	public function __call($method, $args)
	{
		if (in_array($method, array('selectableheader', 'selectionheader')))
		{
			$this->requiredAttributes['data-' . $method] = $args[0];
			return $this;
		}

		return parent::__call($method, $args);
	}

}
