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
	return new Users\Models\Attempt;
});

if ($blockPeriod = Config::get('auth.block_period', 0))
{
	$users = IoC::resolve('UserModel')->where(
		DB::raw("DATEDIFF('" . Carbon::now()->toDateTimeString() . "', last_activity_date)"),
		'>=',
		$blockPeriod)->
		get();

	foreach ($users as $user)
	{
		$user->delete();
	}
}