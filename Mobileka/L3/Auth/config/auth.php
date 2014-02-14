<?php

use \Mobileka\Crud\Form\Components\Text,
	\Mobileka\Crud\Form\Components\Password;

return array(
	'register' => array(
		'template' => 'auth::default.form',
		'action' => array('urlToRoute' => 'auth_default_register'),
		'successUrl' => array('urlToRoute' => 'home'),
		'components' => array(
			'email'    => Text::make('email'),
			'password' => Password::make('password'),
			'name'     => Text::make('name'),
			'phone'    => Text::make('phone'),
		),
		'customData' => array(
			'title' => 'Регистрация',
		),
	),
	'login' => array(
		'template' => 'auth::default.login',
		'action' => array('urlToRoute' => 'auth_default_login'),
		'successUrl' => array('urlToRoute' => 'home'),
		'components' => array(
			'email'    => Text::make('email'),
			'password' => Password::make('password'),
		),
		'customData' => array(
			'title' => 'Войти',
		),
	),
);