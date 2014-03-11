<?php namespace Mobileka\L3\Engine\Laravel\Base;

use Mobileka\L3\Engine\Laravel\Helpers\Misc,
	Mobileka\L3\Engine\Laravel\Router,
	Mobileka\L3\Engine\Laravel\URL,
	Mobileka\L3\Engine\Laravel\File,
	Mobileka\L3\Engine\Laravel\Redirect,
	Mobileka\L3\Engine\Laravel\Input,
	Mobileka\L3\Engine\Laravel\Date,
	Mobileka\L3\Engine\Laravel\Lang,
	Mobileka\L3\Engine\Laravel\Config,
	Mobileka\L3\Engine\Laravel\Acl,
	Mobileka\L3\Engine\Laravel\Base\View,
	Mobileka\L3\Engine\Laravel\Helpers\Arr;

use Mobileka\L3\Engine\Grid\Grid,
	Mobileka\L3\Engine\Form\Form;

use Laravel\Event,
	Laravel\Request,
	Laravel\IoC,
	Laravel\Response;

/**
 * A base controller for CRUD
 *
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
	 * Actions that can only be accessed by the author.
	 *
	 * Example: array('get_view' => 'user_id', 'get_edit' => 'author_id')
	 *
	 * @var array
	 */
	public static $personalActions = array();

	/**
	 * Additional fields coming in POST data, which
	 * must never be saved to database.
	 *
	 * @var array
	 */
	protected static $fieldsToIgnore = array('_method', 'successUrl', 'errorUrl', 'fieldName', 'upload_token');

	/**
	 * Create a new Controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		if (!is_null($this->layout))
		{
			$this->layout = $this->layout();
		}

		static::$route = Misc::currentRoute();

		$this->pageTitle = Lang::findLine('default.controllers.' . static::$route['controller'] . '.titles', static::$route['action']);

		$this->crudConfig = array(
			'form' => Misc::filePath('default.form'),
			'grid' => Misc::filePath('default.grid'),
		);
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
	{}

	/**
	 * Called before each action execution
	 */
	public function before()
	{}

	/**
	 * Called after each action execution
	 *
	 * @param Response $response
	 */
	public function after($response)
	{}

	public function get_index($format = 'html')
	{
		$data = $this->model->buildQuery(
			$this->with,
			$this->conditions,
			$this->order_by,
			$this->per_page
		);

		return $this->layout->renderView(
				array(
					'title' => $this->pageTitle,
					'format' => $format,
					'data' => $data,
					'content' => Grid::make(
						$this->model,
						Config::get($this->crudConfig['grid']),
						$data
					)->render()
				)
			)
		;
	}

	public function get_view($id, $format = 'html')
	{
		if ($this->with)
		{
			$this->model = $this->model->with(static::$with);
		}

		if (!$data = $this->model->find($id) or !$this->checkPersonalAccess(__FUNCTION__, $data))
		{
			return Response::error('404');
		}

		return $this->layout->renderView(
				array(
					'title' => $this->pageTitle,
					'format' => $format,
					'data' => $data,
					'viewData' => array(
						'item' => $data
					)
				)
			)
		;
	}

	public function get_add()
	{
		$this->layout->renderView(
			array(
				'title' => $this->pageTitle,
				'content' => Form::make(
					$this->model,
					Config::get($this->crudConfig['form'])
				)->render()
			)
		);
	}

	public function get_edit($id)
	{
		if (!$data = $this->model->find($id) or !$this->checkPersonalAccess(__FUNCTION__, $data))
		{
			return Response::error('404');
		}

		$this->layout->renderView(
			array(
				'title' => $this->pageTitle,
				'content' => Form::make(
					$data,
					Config::get($this->crudConfig['form'])
				)->render()
			)
		);
	}

	public function post_create()
	{
		$this->data = Input::allBut(static::$fieldsToIgnore);
		return $this->_save();
	}

	public function put_update($id)
	{
		$this->data = Input::allBut(static::$fieldsToIgnore);

		if (!$this->model = $this->model->find($id) or !$this->checkPersonalAccess(__FUNCTION__, $this->model))
		{
			return Response::error('404');
		}

		return $this->_save();
	}

	public function delete_destroy($id)
	{
		if (!$this->model = $this->model->find($id) or !$this->checkPersonalAccess(__FUNCTION__, $this->model))
		{
			return Response::error('404');
		}

		return $this->_destroy();
	}

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

		Event::fire('Model destroyed: ' . Router::requestId(static::$route), array($this->model));

		return Request::ajax()
			? Response::json(array(
				'status' => 'success',
				'errors' => array(),
				'data' => $this->model
			))
			: Redirect::to_action($this->generateUrl($route, $options), $params)
				->success(Lang::findLine('default.messages', 'destroy'));
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

		return Request::ajax()
			? Response::json(array('status' => 'success', 'errors' => array(), 'data' => array()))
			: Redirect::to_action($this->generateUrl($route, $options), $params)
				->success(Lang::findLine('default.messages', 'mass_destroy'));
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

		if (Request::ajax())
		{
			return $this->_ajaxSave();
		}

		$route = static::$route;

		$successUrl = Input::get(
			'successUrl',
			URL::to_action($this->generateUrl($route, $options), $params)
		);

		$errorUrl = Input::get('errorUrl', null);

		$message = Lang::findLine('default.messages', 'create');

		if ($this->model->exists)
		{
			$message = Lang::findLine('default.messages', 'update');
		}

		if (!$this->model->saveData($this->data, $this->safeData))
		{
			if ($this->model->exists)
			{
				//@todo wtf?
				$params = array_merge($params, array('id' => $this->model->id));
			}

			return ($errorUrl)
				? Redirect::to($errorUrl)->
					with_input()->
					with_errors($this->model->errors)
				: Redirect::back()->
					with_input()->
					with_errors($this->model->errors)
			;
		}

		Event::fire('Model saved: ' . Router::requestId(Controller::$route), array($this->model, $oldModel));
		Event::fire('Model saved: ' . Router::requestId(Controller::$route, true), array($this->model, $oldModel));
		Event::fire('bind-uploads', array($this->model->id, Input::get('upload_token', null)));

		return Redirect::to($successUrl)->notify($message, 'success');
	}

	/**
	 * Save a model asynchronously
	 *
	 * @return json
	 */
	protected function _ajaxSave($bindUploads = true, $model = false)
	{
		$this->model = $model ? : $this->model;

		/*
		* Сохраним модель такой, какой она была до сохранения изменения
		* Старая модель нужна для некоторых событий
		*/
		$oldModel = clone $this->model;

		$message = Lang::findLine('default.messages', 'create');

		if ($this->model->exists)
		{
			$message = Lang::findLine('default.messages', 'update');
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

		Event::fire(
			'Model saved async: ' . Router::requestId(Controller::$route),
			array($this->model, $oldModel, $result)
		);

		Event::fire(
			'Model saved async: ' . Router::requestId(Controller::$route, true),
			array($this->model, $oldModel, $result)
		);

		if ($bindUploads)
		{
			Event::fire('bind-uploads', array($this->model->id, Input::get('upload_token', null)));
		}

		return Response::json($result);
	}

	public function get_uploads()
	{
		$this->model = IoC::resolve('uploader');
		return $this->index('json');
	}

	public function get_view_file($id, $uploadId, $format = 'html')
	{
		$uploader = IoC::resolve('Uploader');
		$file = $uploader->find($uploadId);
		$json = array('thumbnail' => \View::make($uploader->template, compact('file'))->render());

		return Response::json($json);
	}

	public function post_upload_file($object_id = 0)
	{
		$this->data = Input::allBut(array('_method', 'successUrl', 'upload_token', 'name', 'fieldName', 'modelName', 'single'));
		$fieldName = Input::get('fieldName', 'file');
		$single = Input::get('single', 0);
		$modelName = str_replace('\\\\', '\\', Input::get('modelName'));
		$uploader = IoC::resolve('Uploader');

		if (!Acl::can('upload_files_without_restrictions') and $object_id)
		{
			$controller = Router::resolve(Controller::$route['alias']);
			$field = Arr::getItem($controller::$personalActions, __FUNCTION__) ?: Arr::getItem($controller::$personalActions, 'all');

			if ($field)
			{
				$isOwner = Acl::isOwnerOfObject($modelName, $field, $object_id);

				if (!$isOwner)
				{
					return Response::json(array(
						'status' => 'error',
						'errors' => array(
							$fieldName => Lang::findLine('default', 'errors.not_owner'),
						),
						'data' => array(),
					));
				}
			}
		}

		$this->data['type'] = $modelName::getTableName();
		$this->data['token'] = Input::get('upload_token');
		$this->data['fieldname'] = $fieldName;
		$this->data['object_id'] = $object_id;
		$this->data['created_at'] = date('Y-m-d H:i:s');

		if ($single)
		{
			$uploader->where_type($this->data['type'])
				->where_fieldname($fieldName)
				->where_object_id($object_id)
				->delete();
		}

		/**
		 * Сохраним файл в папку uploads/$type/YEAR-MONTH
		 */
		$fileData = Input::file($fieldName);

		if ($fileData['error'] != UPLOAD_ERR_OK)
		{
			return Response::json(array(
				'status' => 'error',
				'errors' => array("File upload failed"),
			));
		}

		$this->data['filename'] = File::upload(
			$fileData,
			$this->data['type'] . '/' . \Date::make($this->data['created_at'])->get('Y-m')
		);


		//Сохраняем запись в БД
		return $this->_ajaxSave(false, $uploader);
	}

	public function delete_destroy_file($id)
	{
		$uploader = IoC::resolve('Uploader');

		if (!$file = $uploader->find($id))
		{
			return Response::error('404');
		}

		foreach (Config::find('image.aliases') as $alias => $dimensions)
		{
			$filename = $alias . '_' . $file->filename;
			$path = imagePath($filename, $file->type, $file->created_at);

			if (File::exists($path))
			{
				File::delete($path);
			}
		}

		$path = $file->path;

		if (File::exists($path))
		{
			File::delete($path);
		}

		$file->delete();

		return \Response::json(array('status' => 'success', 'errors' => array(), 'data' => compact($file)));
	}

	protected function checkPersonalAccess($action, $model, $customData = array())
	{
		$field = Arr::getItem(static::$personalActions, $action) ?: Arr::getItem(static::$personalActions, 'all');

		if ($field)
		{
			return (int)$model->$field === (int)uid();
		}

		return true;
	}

	protected function generateUrl($route, $options)
	{
		$route['action'] = Arr::getItem($options, 'action', 'index');
		$route['controller'] = Arr::getItem($options, 'controller', $route['controller']);
		$route['bundle'] = Arr::getItem($options, 'bundle', $route['bundle']);
		return Misc::actionUri($route);
	}

	public function ignoreField($field)
	{
		return $this->ignoreFields($field);
	}

	public function ignoreFields($fields)
	{
		$fields = is_array($fields) ? $fields : array($fields);

		foreach ($fields as $field)
		{
			static::$fieldsToIgnore[] = $field;
		}

		return static::$fieldsToIgnore;
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
		return Response::error('404');
	}

}
