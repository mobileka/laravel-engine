<?php namespace Mobileka\L3\Engine\Form\Components;

class DropdownChosen extends Dropdown {
	protected $template = 'engine::form.dropdown_chosen';
	protected $requiredAttributes = array('class' => 'chosen-select');
	protected $parentAttributes = array('class' => 'input-xlarge');
}
