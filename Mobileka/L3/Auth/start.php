<?php

Autoloader::namespaces(array(
	'Mobileka\L3\Auth' => Bundle::path('auth')
));

use Mobileka\L3\Engine\Form\Form,
	Mobileka\L3\Engine\Form\Components\Text,
	Mobileka\L3\Engine\Form\Components\Password,
	Mobileka\L3\Engine\Laravel\Lang;

IoC::register('authLoginForm', function()
{
	return Form::make(
		IoC::resolve('UserModel'),
		array(
			'template' => Config::get('auth.login_template', 'auth::default.login'),
			'components' => array(
				'email'    => Text::make('email'),
				'password' => Password::make('password'),
			),
			'customData' => array(
				'title' => Lang::findLine('default', 'login'),
			),
		)
	)->setActionUrls('login', array(
		'action' => URL::to_route('auth_default_login'),
		'successUrl' => URL::to_route('home'),
	));
});

IoC::register('authRegisterForm', function()
{
	return Form::make(
		IoC::resolve('UserModel'),
		array(
			'template' => Config::get('auth.register_template', 'auth::default.form'),
			'components' => array(
				'email'    => Text::make('email'),
				'password' => Password::make('password'),
				'name'     => Text::make('name'),
				'phone'    => Text::make('phone'),
			),
			'customData' => array(
				'title' => Lang::findLine('default', 'registration'),
			),
		)
	)->setActionUrls('register', array(
		'action' => URL::to_route('auth_default_register'),
		'successUrl' => URL::to_route('home'),
	));
});

require 'listeners.php';

Event::fire('engine: auth is ready');