<?php

use Mobileka\L3\Engine\Laravel\Base\BackendController;

class Users_Admin_Default_Controller extends BackendController {

	public function __construct()
	{
		$this->model = IoC::resolve('UserModel');
		parent::__construct();
	}
}
