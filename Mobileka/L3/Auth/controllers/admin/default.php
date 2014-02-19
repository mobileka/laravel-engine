<?php

use Carts\Models\Cart,
	Mobileka\L3\Engine\Laravel\Base\BackendController,
	Mobileka\L3\Engine\Laravel\Lang;

class Auth_Admin_Default_Controller extends BackendController {

	protected $user;
	public $layout = 'engine::_system.layouts.admin_login';

	public function __construct(\Users\Models\User $user)
	{
		$this->user = $user;
		parent::__construct();
	}

	public function get_login($format = 'html')
	{
		if (uid())
		{
			return Redirect::to_route('admin_home')
				->notify('Извините, но у Вас нет доступа к этому модулю', 'error');
		}

		$this->layout->renderView();
	}

	public function post_login()
	{
		$credentials = array(
			'username' => Input::get('email'),
			'password' => Input::get('password'),
			'remember' => Input::get('remember', false)
		);

		if (Auth::attempt($credentials))
		{
			Event::fire('successfully_logged_in');
			return Redirect::back()->
				notify(Lang::line('auth::default.successfully_logged_in')->get(), 'success');
		}

		return Redirect::to_route('auth_admin_default_login')->
			with_input()->
			with('error', Lang::findLine('default', 'wrong_username_or_password'))->
			notify(Lang::line('auth::default.wrong_username_or_password')->get(), 'error');
	}

	public function get_logout()
	{
		Auth::logout();
		return Redirect::to_route('auth_admin_default_login')->
			notify(Lang::line('auth::default.successfully_logged_out')->get(), 'success');
	}
}