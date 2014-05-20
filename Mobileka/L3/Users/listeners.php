<?php
use Carbon\Carbon;

Event::listen('engine: users are ready', function()
{
	/**
	 * If block_period is set in auth config file
	 * then find users which are inactive for such period and remove them
	 */
	if (!Request::cli() and $blockPeriod = Config::get('security.block_period', 0))
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
	if (!Auth::guest() and Config::get('security.block_period', 0))
	{
		$user = user();
		$user->last_activity_date = Carbon::now()->toDateTimeString();
		$user->save($user::$rules, array(), null, null, null, function(){ return true; });
	}
});