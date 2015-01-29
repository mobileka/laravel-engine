<?php

use Mobileka\L3\Engine\Form\Form;
use Mobileka\L3\Engine\Grid\Grid;

<formComponents>

<gridComponents>

<filterComponents>

$lists = array(
	<lists>
);

$model = IoC::resolve('<Table>Model');

IoC::register('<bundleName>EngineForm', function () use ($model, $lists) 
{
	$config = array(
		'languageFile' => '<tables>',

		'components' => array(

			<configForm>
			
		),
	);

	return Form::make(
		$model,
		$config
	);
});

IoC::register('<bundleName>EngineGrid', function () use ($model, $lists) 
{
	$config = array(
		'languageFile' => '<tables>',
		
		'components' => array(

			<configGrid>

		),

		'filters' => array(

			<configFilter>

		),
	);

	return Grid::make(
		$model,
		$config
	);
});
