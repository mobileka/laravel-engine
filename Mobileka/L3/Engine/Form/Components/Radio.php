<?php namespace Mobileka\L3\Engine\Form\Components;

class Radio extends Checkbox {

	protected $template = 'engine::form.radio';
	protected $htmlElement = 'radio';
	protected $options = array();
	protected $defaultValue = '';
	protected $requiredAttributes = array('class' => 'icheck-me');

	public function value($lang = '')
	{
		return parent::value($lang) ? : $this->defaultValue;
	}

}
