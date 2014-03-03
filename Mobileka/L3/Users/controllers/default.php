<?php

class Users_Default_Controller extends Base_Controller {

	protected $groupModel;

	public function __construct()
	{
		$this->model = IoC::resolve('UserModel');
		$this->groupModel = IoC::resolve('UserGroupModel');
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
			'data' => $this->model::where('email', 'like', $query.'%')->
				where_group_id($this->groupModel::getIdByCode('users'))->
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
			'data' => $user = ($user = $this->model::where_email($email)->first()) ? $user->to_array() : $user
		);

		return \Response::json($result);
	}
}