<?php

Autoloader::namespaces(array(
	'Auth' => Bundle::path('auth')
));

IoC::register('controller: auth::default', function()
{
	return new Auth_Default_Controller(
		new \Users\Models\User
	);
});

IoC::register('controller: auth::admin.default', function()
{
	return new Auth_Admin_Default_Controller(
		new \Users\Models\User
	);
});
