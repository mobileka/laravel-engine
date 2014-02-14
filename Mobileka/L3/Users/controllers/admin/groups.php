<?php

use Users\Models\Group;

class Users_Admin_Groups_Controller extends Admin_Base_Controller {

	public function __construct()
	{
		$this->model = new Group;

		parent::__construct();

		$this->crudConfig = array(
			'form' => Misc::filePath('groups' . '.form'),
			'grid' => Misc::filePath('groups' . '.grid'),
		);
	}
}