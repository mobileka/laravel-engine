<?php

use Mobileka\L3\Engine\Laravel\Lang,
	Mobileka\L3\Engine\Laravel\Config;

RestfulRouter::make()->get('login', array('as' => 'auth_default_login', 'uses' => 'auth::default@login'));
RestfulRouter::make()->post('login', array('as' => 'auth_default_login', 'uses' => 'auth::default@login'));
RestfulRouter::make()->get('logout', array('as' => 'auth_default_logout', 'uses' => 'auth::default@logout'));
RestfulRouter::make()->get('register', array('as' => 'auth_default_register', 'uses' => 'auth::default@register'));
RestfulRouter::make()->get('recover', array('as' => 'auth_default_recover', 'uses' => 'auth::default@recover'));
RestfulRouter::make()->post('register', array('as' => 'auth_default_register', 'uses' => 'auth::default@register'));

RestfulRouter::make()->get('password_recovery', array('as' => 'auth_default_password_recovery', 'uses' => 'auth::default@password_recovery'));
RestfulRouter::make()->post('password_recovery', array('as' => 'auth_default_password_recovery', 'uses' => 'auth::default@password_recovery'));
RestfulRouter::make()->get('password_recovery_email_sent', array('as' => 'auth_default_password_recovery_email_sent', 'uses' => 'auth::default@password_recovery_email_sent'));
RestfulRouter::make()->get('password_recovery_confirmation/(:any)', array('as' => 'auth_default_password_recovery_confirmation', 'uses' => 'auth::default@password_recovery_confirmation'));

RestfulRouter::make()->get(admin_uri('/login'), array('as' => 'auth_admin_default_login', 'uses' => 'auth::admin.default@login'));
RestfulRouter::make()->post(admin_uri('/login'), array('as' => 'auth_admin_default_login', 'uses' => 'auth::admin.default@login'));
RestfulRouter::make()->get(admin_uri('/logout'), array('as' => 'auth_admin_default_logout', 'uses' => 'auth::admin.default@logout'));

