<?php namespace Mobileka\L3\Engine\Form\Components;

class DropdownChosen extends Dropdown {

	protected $template = 'crud::form.dropdown_chosen';
	protected $requiredAttributes = array(
		'class' => 'chosen-select',
	);

}