<?php namespace Mobileka\L3\Engine\Form\Components;

class Tag extends BaseComponent {

	protected $template = 'engine::form.tag';
	protected $htmlElement = 'textarea';
	protected $requiredAttributes = array('class' => 'tagsinput');

}
