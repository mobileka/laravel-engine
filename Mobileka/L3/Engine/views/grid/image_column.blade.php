<?php
	$route = \Controller::$route;
	$route['action'] = 'edit';
	$route = \Router::requestId($route, true);
?>

<a href="{{ URL::to_route($route, $component->row->id) }}">
	<img src="{{ $component->value() }}" alt="{{ $component->row->name }}">
</a>