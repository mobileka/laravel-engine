<?php

use Mobileka\L3\Engine\Laravel\Lang,
	Mobileka\L3\Engine\Laravel\Config;

RestfulRouter::get('login', array('as' => 'auth_default_login', 'uses' => 'auth::default@login'));
RestfulRouter::post('login', array('as' => 'auth_default_login', 'uses' => 'auth::default@login'));
RestfulRouter::get('logout', array('as' => 'auth_default_logout', 'uses' => 'auth::default@logout'));
RestfulRouter::get('register', array('as' => 'auth_default_register', 'uses' => 'auth::default@register'));
RestfulRouter::get('recover', array('as' => 'auth_default_recover', 'uses' => 'auth::default@recover'));
RestfulRouter::post('register', array('as' => 'auth_default_register', 'uses' => 'auth::default@register'));

RestfulRouter::get('password_recovery', array('as' => 'auth_default_password_recovery', 'uses' => 'auth::default@password_recovery'));
RestfulRouter::post('password_recovery', array('as' => 'auth_default_password_recovery', 'uses' => 'auth::default@password_recovery'));
RestfulRouter::get('password_recovery_email_sent', array('as' => 'auth_default_password_recovery_email_sent', 'uses' => 'auth::default@password_recovery_email_sent'));
RestfulRouter::get('password_recovery_confirmation/(:any)', array('as' => 'auth_default_password_recovery_confirmation', 'uses' => 'auth::default@password_recovery_confirmation'));

RestfulRouter::get('admin/login', array('as' => 'auth_admin_default_login', 'uses' => 'auth::admin.default@login'));
RestfulRouter::post('admin/login', array('as' => 'auth_admin_default_login', 'uses' => 'auth::admin.default@login'));
RestfulRouter::get('admin/logout', array('as' => 'auth_admin_default_logout', 'uses' => 'auth::admin.default@logout'));

