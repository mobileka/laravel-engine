<?php
use Carbon\Carbon;

Event::listen('engine: ready', function()
{
	Route::filter('csrf', function()
	{
		$token = Input::get(Session::csrf_token);

		if (Request::ajax())
		{
			$token = is_array($token = Request::header('x-csrf-token')) ? Arr::getItem($token, 0) : $token;
		}

		if (Session::token() != $token)
		{
			return Response::make('<h3 style="color:#d00">Invalid authenticity token</h3>', 403);
		}
	});

	if (Config::get('application.ssl') and !Request::secure())
	{
		header('Location: '.URL::to(URI::current(), true, false, false));
		exit();
	}
});

Event::listen('engine: users are ready', function()
{
	/**
	 * If block_period is set in auth config file
	 * then find users which are inactive for such period and remove them
	 */
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

	//Save last user activity date
	if (!Auth::guest() and Config::get('auth.block_period', 0))
	{
		$user = user();
		$user->last_activity_date = Carbon::now()->toDateTimeString();
		$user->save();
	}
});