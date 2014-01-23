<?php

function notifications($view = '_system.notifications')
{
	return \Notification::printAll($view);
}

function money($number)
{
	return number_format($number, 2, '.', ' ');
}