<?php

use \Users\Models\User,
	\Users\Models\Group;

function isAdmin($user = null)
{
	return group($user) === 'admins';
}

function isManager()
{
	return in_array(group(), Group::$types['managers']);
}

function user()
{
	if (!Auth::check())
	{
		return new User;
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