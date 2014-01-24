<?php namespace Mobileka\L3\Engine\Laravel\Base;

class BackendController extends Controller {

	public $layout = 'engine::layouts.admin';

	public function __construct()
	{
		/**
		 * Надо ли добавить сюда авторизацию сразу?
		 * В таком случае надо будет в CRUD интегрировать бандл Auth еще :)
		 */
		//$this->filter('before', 'adminAuth');
		parent::__construct();
	}

}