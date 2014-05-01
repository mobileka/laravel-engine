<?php

function array_average(Array $array, $ignore_falsy = true)
{
	$sum   = 0;
	$count = 0;

	foreach ($array as $value)
	{
		$sum += $value;

		if ($value or !$ignore_falsy) ++$count;
	}

	return $count ? $sum / $count : 0;
}