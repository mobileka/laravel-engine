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

function dateTimeToDate($datetime, $now = false)
{
	$default = ($now) ? Date::make(date('Y-m-d H:i:s'))->get() : '';

	return ($datetime != '0000-00-00 00:00:00' and $datetime) ? Date::make($datetime)->get() : $default;
}

function translateDate($date, $lang = '', $delimiter = ' ')
{
	$date = substr($date, 0, 10);
	$result = array();
	$dayIndex = 2;
	$monthIndex = 1;
	$yearIndex = 0;
	$date = explode('-', $date);
	$lang = $lang ? : getCurrentLang();

	$result[0] = Arr::getItem($date, $dayIndex, '01');
	$month = (int)Arr::getItem($date, $monthIndex, 1);
	$result[1] = Lang::line('months.'.($month - 1), array(), $lang)->get();
	$result[2] = Arr::getItem($date, $yearIndex, '1970');
	ksort($result);

	return implode($delimiter, $result);
}