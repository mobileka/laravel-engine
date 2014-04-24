<?php

Bundle::start('engine');

Autoloader::namespaces(array(
	'Mobileka\L3\Users' => Bundle::path('users')
));

IoC::register('UserModel', function()
{
	return new Mobileka\L3\Users\Models\User;
});

IoC::register('UserGroupModel', function()
{
	return new Mobileka\L3\Users\Models\Group;
});

IoC::register('UserLoginAttemptModel', function()
{
	return new Mobileka\L3\Users\Models\Attempt;
});

require 'listeners.php';

Event::fire('engine: users are ready');
