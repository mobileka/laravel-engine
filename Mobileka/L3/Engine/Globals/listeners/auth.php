<?php

Event::listen('engine: auth is ready', function()
{
	Route::filter('adminAuth', function()
	{
		$allowed = Config::get('acl.allowedRoutes', array());

		if (!Acl::make()->except($allowed)->check())
		{
			return Redirect::to_route('auth_admin_default_login')
				->with('error', Lang::findLine('default', 'no_access'));
		}
	});
});

Event::listen('successfully_logged_in', function()
{
	Acl::clearLoginAttempts(user()->username);
});

Event::listen('unsuccessful_login_attempt', function($credentials)
{
	Acl::incLoginAttempts($credentials['username']);
});