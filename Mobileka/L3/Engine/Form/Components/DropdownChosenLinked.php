<?php namespace Mobileka\L3\Engine\Form\Components;

class DropdownChosenLinked extends DropdownChosen {

	protected $template = 'engine::form.dropdown_chosen_linked';
	protected $requiredAttributes = array('class' => 'chosen-select');
	protected $parentAttributes = array('class' => 'input-xlarge');
	protected $linked_items = array();

}