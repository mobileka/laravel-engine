<?php namespace Mobileka\Crud\Form\Components;

class Tag extends BaseComponent {

	protected $template = 'crud::form.tag';
	protected $requiredAttributes = array(
		'class' => 'tagsinput',
	);

}