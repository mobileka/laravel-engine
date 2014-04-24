<?php

use Mobileka\L3\Engine\Laravel\RestfulRouter,
	Carbon\Carbon;

RestfulRouter::make()->get('users/emails', array('as' => 'users_default_emails', 'uses' => 'users::default@emails'));
RestfulRouter::make()->get('users/byEmail', array('as' => 'users_default_byEmail', 'uses' => 'users::default@byEmail'));

RestfulRouter::make()->get('admin/singin', array('as' => 'users_admin_default_signin', 'uses' => 'users::admin.default@signin'));
RestfulRouter::make()->post('admin/singin', array('as' => 'users_admin_default_signin', 'uses' => 'users::admin.default@signin'));
RestfulRouter::make()->except('view')->resource(array('submodule' => 'admin', 'bundle' => 'users'));

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
