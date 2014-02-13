<?php namespace Mobileka\L3\Engine\Form\Components;

class Radio extends Checkbox {

	protected $template = 'engine::form.radio';
	protected $htmlElement = 'radio';
	protected $options = array();
	protected $requiredAttributes = array('class' => 'icheck-me');

}
