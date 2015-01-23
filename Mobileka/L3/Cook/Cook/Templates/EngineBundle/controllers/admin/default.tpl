<?php

use Base\Controllers\Backend\Controller as BackendController;

class <controllerPrefix>_Admin_<Tables>_Controller extends BackendController {

	public function __construct()
	{
		parent::__construct();

		$this->layout->title = ___('<tables>.controllers.admin.<tables>.titles', static::$route['action']);
		
		$this->model = IoC::resolve('<Table>Model');

		<with>

	}

} 

