<?php namespace Mobileka\Crud\Form\Components;

class Email extends BaseComponent {

	protected $template = 'crud::form.email';

	protected $requiredAttributes = array(
		'class' => 'input-medium',
	);

}