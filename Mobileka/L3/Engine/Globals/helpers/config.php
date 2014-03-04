<?php

use Mobileka\L3\Engine\Laravel\Config;

function configValue($key, $defaultValue = null)
{
	return Config::get($key, $defaultValue);
}