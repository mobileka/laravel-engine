<?php

use Mobileka\L3\Engine\Laravel\Lang,
	Mobileka\L3\Engine\Laravel\Config;

Route::get('login', array('as' => 'auth_default_login', 'uses' => 'auth::default@login'));
Route::post('login', array('as' => 'auth_default_login', 'uses' => 'auth::default@login'));
Route::get('logout', array('as' => 'auth_default_logout', 'uses' => 'auth::default@logout'));
Route::get('register', array('as' => 'auth_default_register', 'uses' => 'auth::default@register'));
Route::get('recover', array('as' => 'auth_default_recover', 'uses' => 'auth::default@recover'));
Route::post('register', array('as' => 'auth_default_register', 'uses' => 'auth::default@register'));

Route::get('password_recovery', array('as' => 'auth_default_password_recovery', 'uses' => 'auth::default@password_recovery'));
Route::post('password_recovery', array('as' => 'auth_default_password_recovery', 'uses' => 'auth::default@password_recovery'));
Route::get('password_recovery_email_sent', array('as' => 'auth_default_password_recovery_email_sent', 'uses' => 'auth::default@password_recovery_email_sent'));
Route::get('password_recovery_confirmation/(:any)', array('as' => 'auth_default_password_recovery_confirmation', 'uses' => 'auth::default@password_recovery_confirmation'));

Route::get(admin_uri('/login'),  array('as' => 'auth_admin_default_login', 'uses' => 'auth::admin.default@login'));
Route::post(admin_uri('/login'), array('as' => 'auth_admin_default_login', 'uses' => 'auth::admin.default@login'));
Route::get(admin_uri('/logout'), array('as' => 'auth_admin_default_logout', 'uses' => 'auth::admin.default@logout'));

Route::filter('adminAuth', function()
{
	$allowed = Config::get('acl.allowedRoutes', array());

	if (!Acl::make()->except($allowed)->check())
	{
		return Redirect::to_route('auth_admin_default_login')
			->with('error', Lang::findLine('default', 'no_access'));
	}
});

