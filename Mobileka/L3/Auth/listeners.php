<?php

use Mobileka\L3\Engine\Laravel\Config,
	Mobileka\L3\Engine\Laravel\Lang;

/**
 * Regenerate csrf_token on logout and login
 */
Event::listen('laravel.auth: login', function()
{
	Session::put(Session::csrf_token, Str::random(40));
});

Event::listen('laravel.auth: logout', function()
{
	Session::put(Session::csrf_token, Str::random(40));
});

Event::listen('engine: auth is ready', function()
{
	Route::filter('adminAuth', function()
	{
		$restrictByIp = ($ip = Config::get('security.admin_ip'))
			? !(Request::ip() == $ip)
			: false
		;

		$restrictByPort = ($port = Config::get('security.admin_port', false))
			? !(Request::foundation()->getPort() == $port)
			: false
		;

		if ($restrictByIp or $restrictByPort)
		{
			return Response::error('404');
		}

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

Event::listen('successfully_logged_in', function()
{
	Acl::clearLoginAttempts(user()->username);
});

Event::listen('unsuccessful_login_attempt', function($credentials)
{
	if ($username = Arr::getItem($credentials, 'username'))
	{
		Acl::incLoginAttempts($username);
	}
});

Event::listen('A new user was created', function($model, $password)
{

});

Event::listen('A new password recovery request was sent', function($user)
{

});

Event::listen('A password was successfully recovered', function($user, $newPassword)
{

});