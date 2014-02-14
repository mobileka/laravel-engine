<?php

use Mobileka\L3\Engine\Form\Components\Text,
	Mobileka\L3\Engine\Grid\Components\Column;

return array(
	'form' => array(
		'languageFile' => 'groups',
		'components' => array(
			'name' => Text::make('name'),
			'code' => Text::make('code'),
		),
	),

	'grid' => array(
		'languageFile' => 'groups',
		'components' => array(
			'name' => Column::make('name'),
			'code' => Column::make('code'),
		),
	),
);
