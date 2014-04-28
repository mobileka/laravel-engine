<?php

use Mobileka\L3\Engine\Laravel\Lang,
	Mobileka\L3\Engine\Laravel\Acl;

class Auth_Default_Controller extends Base_Controller {

	protected $groupModel;

	public function __construct()
	{
		$this->model = IoC::resolve('UserModel');
		$this->groupModel = IoC::resolve('UserGroupModel');
		$this->layout = Config::get('auth.layout', $this->layout);
		parent::__construct();
	}

	public function get_login($format = 'html')
	{
		if (uid())
		{
			return Redirect::to_route('home')
				->notify(Lang::line('auth::default.already_logged_in')->get(), 'error');
		}

		$this->layout->renderView(array(
			'content' => IoC::resolve('authLoginForm')->render()
		));
	}

	public function post_login()
	{
		$credentials = array(
			'username' => Input::get('email'),
			'password' => Input::get('password'),
			'remember' => Input::get('remember', false)
		);

		if (Acl::isTooMuchLoginAttempts($credentials['username']))
		{
			return Redirect::to_route('auth_default_login')->
				with_input()->
				with('error', Lang::findLine('default', 'too_much_login_attempts'))->
				notify(Lang::findLine('default', 'too_much_login_attempts'), 'error');
		}

		if (Auth::attempt($credentials))
		{
			Event::fire('successfully_logged_in');
			return Redirect::back()->
				notify(Lang::line('auth::default.successfully_logged_in')->get(), 'success');
		}

		Event::fire('unsuccessful_login_attempt', array($credentials));

		return Redirect::to_route('auth_default_login')->
			with_input()->
			with('error', Lang::findLine('default', 'wrong_username_or_password'))->
			notify(Lang::line('auth::default.wrong_username_or_password')->get(), 'error');
	}

	public function get_logout()
	{
		Auth::logout();
		return Redirect::to_route('home')->
			notify(Lang::line('auth::default.successfully_logged_out')->get(), 'success');
	}

	public function get_register()
	{
		$this->layout->renderView(array(
			'content' => IoC::resolve('authRegisterForm')->render()
		));
	}

	/**
	 * Регистрация пользователя при попытке оформления заказа без авторизации
	 */
	public function post_register()
	{
		$data = Input::allBut('successUrl');
		$data['group_id'] = $this->groupModel->getIdByCode('users');

		if (!$this->model->saveData($data))
		{
			return \Redirect::to_route('auth_default_register')->
				with_input()->
				with_errors($this->model->errors);
		}

		Event::fire('A new user was created', array($this->model, $data['password']));

		//если пользователь был создан, то автоматически авторизуем его
		$authenticated = \Auth::attempt(
			array(
				'username' => $this->model->email,
				'password' => $data['password'],
				'remember' => true
			)
		);

		if (!$authenticated)
		{
			return \Redirect::back()->
				with_input()->
				notify(\Lang::findLine('default', 'system_error'), 'error');
		}

		Event::fire('successfully_logged_in');

		return Redirect::to_route('home')->
			notify(Lang::findLine('default', 'automatically_logged_in'), 'success');
	}

	public function get_password_recovery()
	{
		$this->layout->renderView();
	}

	public function post_password_recovery()
	{
		$validation = Validator::make(
			Input::get(),
			array(
				'email' => 'required|email|exists:users,email',
				'password' => 'required|min:6|confirmed'
			)
		);

		if ($validation->fails())
		{
			return Redirect::to_route('auth_default_password_recovery')->
				with_input()->
				with_errors($validation->errors);
		}

		$email = Input::get('email');
		$password = Input::get('password');
		$token = \Misc::randomPassword(32, 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890');

		$user = $this->model->where_email($email)->first();

		/**
		 * Проверять существование пользователя нет смысла, потому что это уже делает валидация выше
		 */

		$user->recovery_token = $token;
		/**
		 * @todo: Auth::plainPasswordAttempt()
		 * Необходимо реализовать возможность авторизовать пользователя без хэширования пароля
		 * Это позволит записывать в recovery_password хэш, а не открытый пароль
		 */
		$user->recovery_password = $password;
		$user->recovery_request_date = date('Y-m-d H:i:s');
		$user->save();

		Event::fire('A new password recovery request was sent', array($user));

		return Redirect::to_route('auth_default_password_recovery_email_sent', compact('email'));
	}

	public function get_password_recovery_email_sent()
	{
		$this->layout->renderView();
	}

	public function get_password_recovery_confirmation($token)
	{
		$user = $this->model->where_recovery_token($token)->first();
		$numberOfValidDays = Config::get('auth::recovery.recovery_link_valid_days');
		$now = Date::make()->subDays($numberOfValidDays)->get();

		//если (сегодня - 7 дней) больше даты подачи заявки, то просрочено
		if (!$user or ($now > $user->recovery_request_date))
		{
			return \Redirect::to_route('auth_default_password_recovery')->
				notify(\Lang::findLine('default', 'password_recovery_link_is_expired'), 'error');
		}

		$newPassword = $user->recovery_password;
		$user->password = $newPassword;
		$user->recovery_password = '';
		$user->recovery_token = '';
		$user->recovery_request_date = '0000-00-00 00:00:00';
		$user->save();

		$credentials = array(
			'username' => $user->email,
			'password' => $newPassword,
			'remember' => true
		);

		/**
		 * @todo: Auth::plainPasswordAttempt()
		 * Необходимо реализовать возможность авторизовать пользователя без хэширования пароля
		 */
		if (Auth::attempt($credentials))
		{
			Event::fire('successfully_logged_in');
			Event::fire('A password was successfully recovered', array($user, $newPassword));
			return Redirect::to_route('home')->
				notify(Lang::line('auth::default.automatically_logged_in')->get(), 'success');
		}

		//@wtf вот сюда никогда не должно заходить
		return \Redirect::to_route('auth_default_password_recovery')->
			notify(\Lang::findLine('default', 'password_recovery_link_is_expired'), 'error');
	}
}