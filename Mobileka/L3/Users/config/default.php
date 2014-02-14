<?php

use Mobileka\L3\Engine\Form\Components\Text,
	Mobileka\L3\Engine\Form\Components\TextArea,
	Mobileka\L3\Engine\Form\Components\Password,
	Mobileka\L3\Engine\Form\Components\Dropdown,
	Mobileka\L3\Engine\Grid\Components\Column,
	Mobileka\L3\Engine\Grid\Filters\Contains,
	Mobileka\L3\Engine\Grid\Filters\Dropdown as DropdownFilter;

use Users\Models\Group;

$groups = Group::lists('name', 'id');

return array(
	'form' => array(
		'components' => array(
			'email' => Text::make('email'),
			'password' => Password::make('password'),
			'group_id' => Dropdown::make('group_id')->options($groups),
			'name' => Text::make('name'),
			'contacts' => TextArea::make('contacts'),
		),
	),

	'grid' => array(
		'components' => array(
			'email' => Column::make('email'),
			'name' => Column::make('name'),
		),

		'filters' => array(
			'email' => Contains::make('email'),
			'name' => Contains::make('name'),
			'group_id' => DropdownFilter::make('group_id')->options($groups),
		),
	),
);
