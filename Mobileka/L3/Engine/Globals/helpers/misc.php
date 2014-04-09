<?php

function notifications($view = 'engine::_system.notifications')
{
	return \Notification::printAll($view);
}

function money($number)
{
	return number_format($number, 2, '.', ' ');
}

function csrf_token()
{
	return Session::token();
}