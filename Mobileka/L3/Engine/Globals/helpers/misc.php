<?php

function notifications($view = 'engine::_system.notifications')
{
	return \Notification::printAll($view);
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