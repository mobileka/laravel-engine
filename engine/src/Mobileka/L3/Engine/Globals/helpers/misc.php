<?php

use \InfoBlocks\Models\InfoBlock;

function notifications($view = '_system.notifications')
{
	return Notification::printAll($view);
}

function money($number)
{
	return number_format($number, 2, '.', ' ');
}

function infoblock($alias)
{
	return InfoBlock::where_alias($alias)->first()->content;
}