<?php

use Laravel\Event;
use Laravel\Routing\Route;
use Laravel\Request;
use Mobileka\L3\Engine\Laravel\Config;

Event::listen('engine: ready', function()
{
	Route::filter('engine_csrf', function()
	{
		$token = (!Request::ajax() and Request::method() === 'GET') ? Session::token()  : Input::get(Session::csrf_token);

		if (Request::ajax())
		{
			$token = is_array($token = Request::header('x-csrf-token')) ? Arr::getItem($token, 0) : $token;
		}

		if (Session::token() != $token)
		{
			return Response::make('<h3 style="color:#d00">Invalid authenticity token</h3>', 403);
		}
	});

	if (Config::get('application.ssl') and !Request::secure() and !Request::cli())
	{
		header('Location: '.URL::to(URI::current(), true, false, false), true, 301);
		exit;
	}
});

Event::listen('bind-uploads', function($id, $tokens)
{
	if (!is_array($tokens))
	{
		$tokens = array($tokens);
	}

	if ($id and $tokens)
	{
		foreach ($tokens as $fieldName => $token)
		{
			$model = IoC::resolve('Uploader');
			$model->where_token($token)->
				update(array('object_id' => (int)$id));
		}

		return true;
	}

	return false;
});

Event::listen('engine: append headers', function($response, $headers = array())
{
	foreach ($headers as $header => $value)
	{
		$response->header($header, $value);		
	}
});