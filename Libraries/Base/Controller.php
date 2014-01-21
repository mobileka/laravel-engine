<?php namespace Base;

use Helpers\Misc, Helpers\Arr;

/**
 * @author Armen Markossyan <a.a.markossyan@gmail.com>
 * @version 2.0
 */
class Controller extends \Laravel\Routing\Controller {

	/**
	 * Holds information about a current route
	 */
	public static $route = array();

	/**
	 * Make all actions restful by default
	 */
	public $restful = true;

	/**
	 * Sets a default page layout
	 */
	public $layout = '_system.layouts.mikhalych_inner';

	/**
	 * Holds an instance of a main model of a current contorller
	 */
	protected $model;

	/**
	 * An array of related models to join with
	 */
	protected $with = array();

	/**
	 * An array of conditions for a current DB call
	 */
	protected $conditions = array();

	/**
	 * Sorting rules for a current DB call
	 */
	protected $order_by = array();

	/**
	 * How many records should be displayed per page
	 */
	protected $per_prage = null;

	/**
	 * Data to be saved by saveData() model method
	 * Is being filled from Input::get() by default
	 */
	protected $data = array();

	/**
	 * Data to be saved by saveData() model method
	 * Is being set by hand in a controller
	 */
	protected $safeData = array();

	/**
	 * Create a new Controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		if (! is_null($this->layout))
		{
			$this->layout = $this->layout();
		}

		static::$route = Misc::currentRoute();
	}

	/**
	 * Create the layout that is assigned to the controller.
	 *
	 * @return View
	 */
	public function layout()
	{
		if (starts_with($this->layout, 'name: '))
		{
			return View::of(substr($this->layout, 6));
		}

		return View::make($this->layout);
	}

	/**
	 * Filters for admin panel
	 */
	public function _admin_filters()
	{
		//$this->filter('before', 'admin_acl');
	}

	/**
	 * Called before each action execution
	 */
	public function before()
	{
		\Helpers\Debug::log_pp(static::$route);
		//Geo::instance()->determine_city();
	}

	/**
	 * Called after each action execution
	 *
	 * @param Response $response
	 */
	public function after($response)
	{}

	/**
	 * Delete a single model
	 *
	 * @param array $options
	 * @param array $params
	 * @return \Redirect | json
	 */
	public function _destroy($options = array(), $params = array())
	{
		$route = Misc::currentRoute();
		$this->model->delete();

		\Event::fire('Model destroyed: ' . \Router::requestId(Controller::$route), array($this->model));

		return \Request::ajax()
			? \Response::json(array(
				'status' => 'success',
				'errors' => array(),
				'data' => $this->model
			))
			: \Redirect::to_action($this->generateUrl($route, $options), $params)
				->success(\Lang::findLine('default.messages', 'destroy'));
	}

	/**
	 * Batch delete
	 *
 	 * @param array $options
	 * @param array $params
	 * @return \Redirect | json
	 */
	public function _mass_destroy($options = array(), $params = array())
	{
		$route = Misc::currentRoute();

		$ids = (array_key_exists('selected_rows', $options))
			? Input::get($options['selected_rows'])
			: Input::get('selected_rows')
		;

		foreach ($ids as $id)
		{
			$this->model->find($id)->delete();
		}

		return \Request::ajax()
			? \Response::json(array('status' => 'success', 'errors' => array(), 'data' => array()))
			: \Redirect::to_action($this->generateUrl($route, $options), $params)
				->success(\Lang::findLine('default.messages', 'mass_destroy'));
	}

	/**
	 * Save a model
	 *
 	 * @param array $options
	 * @param array $params
	 * @return \Redirect | json
	 */
	protected function _save($options = array(), $params = array())
	{
		/*
		* Сохраним модель такой, какой она была до сохранения изменений
		* Это нужно для некоторых событий, которые вызываются ниже
		*/
		$oldModel = clone $this->model;

		if (\Request::ajax())
		{
			return $this->_ajaxSave();
		}

		$route = static::$route;

		$successUrl = \Input::get(
			'successUrl',
			\URL::to_action($this->generateUrl($route, $options), $params)
		);

		$errorUrl = \Input::get('errorUrl', null);

		$message = \Lang::findLine('default.messages', 'create');

		if ($this->model->exists)
		{
			$message = \Lang::findLine('default.messages', 'update');
		}

		if (!$this->model->saveData($this->data, $this->safeData))
		{
			if ($this->model->exists)
			{
				//@todo wtf?
				$params = array_merge($params, array('id' => $this->model->id));
			}

			return ($errorUrl)
				? \Redirect::to($errorUrl)->
					with_input()->
					with_errors($this->model->errors)
				: \Redirect::back()->
					with_input()->
					with_errors($this->model->errors)
			;
		}

		\Event::fire('Model saved: ' . \Router::requestId(Controller::$route), array($this->model, $oldModel));
		\Event::fire('Model saved: ' . \Router::requestId(Controller::$route, true), array($this->model, $oldModel));
		\Event::fire('bind-uploads', array($this->model->id, \Input::get('upload_token', null)));

		return \Redirect::to($successUrl)->notify($message, 'success');
	}

	/**
	 * Save a model asynchronously
	 *
	 * @return json
	 */
	protected function _ajaxSave()
	{
		/*
		* Сохраним модель такой, какой она была до сохранения изменения
		* Старая модель нужна для некоторых событий
		*/
		$oldModel = clone $this->model;

		$message = \Lang::findLine('default.messages', 'create');

		if ($this->model->exists)
		{
			$message = \Lang::findLine('default.messages', 'update');
		}

		$result = array(
			'status' => 'success',
			'errors' => array(),
			'data' => array()
		);

		if (!$this->model->saveData($this->data, $this->safeData))
		{
			$result['status'] = 'error';
			$result['errors'] = $this->model->errors;
		}

		$result['data'] = $this->model->to_array();

		\Event::fire(
			'Model saved async: ' . \Router::requestId(Controller::$route),
			array($this->model, $oldModel, $result)
		);

		\Event::fire(
			'Model saved async: ' . \Router::requestId(Controller::$route, true),
			array($this->model, $oldModel, $result)
		);

		\Event::fire('bind-uploads', array($this->model->id, \Input::get('upload_token', null)));

		return \Response::json($result);
	}

	protected function generateUrl($route, $options)
	{
		$route['action'] = Arr::getItem($options, 'action', 'index');
		$route['controller'] = Arr::getItem($options, 'controller', $route['controller']);
		$route['bundle'] = Arr::getItem($options, 'bundle', $route['bundle']);
		return Misc::actionUri($route);
	}

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return \Response::error('404');
	}

}