<?php

use Users\Models\User,
	Users\Models\Group;

class Users_Default_Controller extends Base_Controller {

	public function __construct()
	{
		$this->model = new User;
		return parent::__construct();
	}

	public function get_emails()
	{
		if (!isManager() or !\Request::ajax())
		{
			exit;
		}

		$query = Input::get('query');

		$result = array(
			'status' => 'success',
			'errors' => array(),
			'data' => User::where('email', 'like', $query.'%')->
				where_group_id(Group::getIdByCode('users'))->
				lists('email')
		);

		return \Response::json($result);
	}

	public function get_byEmail()
	{
		if (!isManager() or !\Request::ajax())
		{
			exit;
		}

		$email = Input::get('email');

		$result = array(
			'status' => 'success',
			'errors' => array(),
			'data' => $user = ($user = User::where_email($email)->first()) ? $user->to_array() : $user
		);

		return \Response::json($result);
	}
}