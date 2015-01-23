<?php

class <controllerPrefix>_Admin_<Tables>_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->model = IoC::resolve('<Table>Model');

		<with>
	}

} 

