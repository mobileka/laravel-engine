<?php namespace Mobileka\Crud\Form\Components;

class Radio extends Checkbox {

	protected $template = 'crud::form.radio';
	protected $options = array();

	protected $requiredAttributes = array(
		'class' => 'icheck-me',
	);

}