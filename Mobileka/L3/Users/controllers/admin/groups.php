<?php

use Mobileka\L3\Engine\Laravel\Base\BackendController;

class Users_Admin_Groups_Controller extends BackendController {

	public function __construct()
	{
		$this->model = IoC::resolve('UserGroupModel');

		parent::__construct();

		$this->crudConfig = array(
			'form' => Misc::filePath('groups' . '.form'),
			'grid' => Misc::filePath('groups' . '.grid'),
		);
	}
}