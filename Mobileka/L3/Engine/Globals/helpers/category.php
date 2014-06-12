<?php

function show_tree($categories, $components)
{
    return Laravel\View::make('engine::grid._grid_nested', compact('categories', 'components'));
}
