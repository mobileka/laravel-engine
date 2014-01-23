<?php

function show_tree($categories, $components) {
	return \View::make('engine::grid._grid_nested', compact('categories', 'components'));
}