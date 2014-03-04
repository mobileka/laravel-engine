<?php

use Mobileka\L3\Engine\Laravel\Date;

function years($startFrom = 2012)
{
	$years = array();

	for ($i = $startFrom, $current_year = date('Y'); $i <= $current_year; $i++)
	{
		$years[$i] = $i;
	}

	return $years;
}

function month()
{
	return date('n');
}

function year()
{
	return date('Y');
}

function day()
{
	return date('d');
}

function dateTimeToDate($datetime)
{
	return ($datetime != '0000-00-00 00:00:00' and $datetime) ? Date::make($datetime)->get() : '';
}

function translateDate($date, $lang = 'ru', $delimiter = '-', $dayIndex = 0, $monthIndex = 1)
{
	return \Date::translate($date, $lang, $delimiter, $dayIndex, $monthIndex);
}