<?php

function admin_uri($append = '')
{
	$uri = Config::get('security.admin_uri', 'admin');

	return  $uri . $append;
}

function notifications($view = 'engine::_system.notifications', $id = '')
{
	return \Notification::printAll($view, $id);
}

function money($number)
{
	return number_format($number, 2, '.', ' ');
}

function token()
{
	return Session::token();
}

function csrf_meta_tag($view = 'engine::_system.csrf')
{
	return View::make($view)->render();
}

function stats_format($number)
{
	$decimal = strpos($number, '.') !== false ? 1 : 0;

	return number_format($number, $decimal, ', ', ' ');
}