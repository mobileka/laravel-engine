<?php

function isAdmin($user = null)
{
	return group($user) === 'admins';
}

function user()
{
	if (!Auth::check())
	{
		return IoC::resolve('UserModel');
	}

	return Auth::user();
}

function uid()
{
	return user()->id;
}

function group($user = null)
{
	$user = ($user) ? : user();

	if ($group = $user->group)
	{
		return $group->code;
	}

	return null;
}

function userName()
{
	$user = user();
	return ($user->name) ?: Lang::findLine('default', 'user_name_is_empty');
}