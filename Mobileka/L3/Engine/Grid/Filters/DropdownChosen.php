<?php namespace Mobileka\L3\Engine\Grid\Filters;

class DropdownChosen extends Dropdown {

	protected $template = 'engine::grid.filters.dropdown_chosen';
	protected $requiredAttributes = array('class' => 'chosen-select');
	protected $parentAttributes = array('class' => 'input-xlarge');

}
