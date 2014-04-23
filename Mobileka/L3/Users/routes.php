<?php

use Mobileka\L3\Engine\Laravel\RestfulRouter,
	Carbon\Carbon;

Route::filter('before', function()
{
	if (!Auth::guest() and Config::get('auth.block_period', 0))
	{
		$user = user();
		$user->last_activity_date = Carbon::now()->toDateTimeString();
		$user->save();
	}
});

RestfulRouter::get('users/emails', array('as' => 'users_default_emails', 'uses' => 'users::default@emails'));
RestfulRouter::get('users/byEmail', array('as' => 'users_default_byEmail', 'uses' => 'users::default@byEmail'));

RestfulRouter::make()->except('view')->resource(array('submodule' => 'admin', 'bundle' => 'users'));
RestfulRouter::get('admin/singin', array('as' => 'users_admin_default_signin', 'uses' => 'users::admin.default@signin'));
RestfulRouter::post('admin/singin', array('as' => 'users_admin_default_signin', 'uses' => 'users::admin.default@signin'));

/* Groups */
RestfulRouter::make()
	->except('view')
	->resource(
		array(
			'submodule' => 'admin',
			'bundle' => 'users',
			'controller' => 'groups'
		)
	);
