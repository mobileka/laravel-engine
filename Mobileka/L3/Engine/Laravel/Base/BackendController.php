<?php namespace Mobileka\L3\Engine\Laravel\Base;

class BackendController extends Controller {

	public $layout = 'engine::_system.layouts.admin';

	public function __construct()
	{
		$this->filter('before', 'adminAuth');
		parent::__construct();
	}

}
