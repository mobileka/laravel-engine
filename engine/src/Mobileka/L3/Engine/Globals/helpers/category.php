<?php

function show_tree($categories, $components) {
	return View::make('crud::grid._grid_nested', compact('categories', 'components'));
}