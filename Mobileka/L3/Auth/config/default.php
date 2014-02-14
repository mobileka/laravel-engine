<?php
use \Mobileka\Crud\Form\Components\Text,
	\Mobileka\Crud\Form\Components\Password,
	\Mobileka\Crud\Form\Components\Checkbox;

return array(
	'register' => array(
		'action' => array('urlToRoute' => 'auth_default_register'),
		'successUrl' => array('urlToRoute' => 'auth_default_register'),
		'components' => array(
			'email' => Text::make('email'),
			'password' => Password::make('password'),
			'password_confirmation' => Password::make('password_confirmation'),
		)
	),
	'login' => array(
		'action' => array('urlToRoute' => 'auth_default_login'),
		'successUrl' => array('urlToRoute' => 'home'),
		'components' => array(
			'email' => Text::make('email'),
			'password' => Password::make('password'),
			//'remember_me' => Checkbox::make('remember_me')
		)
	),
	'password_recovery' => array(
		'action' => array('urlToRoute' => 'auth_default_password_recovery'),
		'successUrl' => array('urlToRoute' => 'auth_default_password_recovery_email_sent'),
		'components' => array(
			'email' => Text::make('email'),
			'password' => Password::make('password'),
			'password_confirmation' => Password::make('password_confirmation'),
		)
	)
);