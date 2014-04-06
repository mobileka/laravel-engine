<?php namespace Mobileka\L3\Engine\Laravel;

use Mobileka\L3\Engine\Laravel\Helpers\Arr;

class RestfulRouter {

	public $actions = array(
		'index',
		'view',
		'add',
		'edit',
		'create',
		'update',
		'destroy'
	);

	public static function make()
	{
		return new static;
	}

	public function resource(array $options)
	{
		$submodule = Arr::getItem($options, 'submodule', '');
		$bundle = Arr::getItem($options, 'bundle', '');
		$controller = Arr::getItem($options, 'controller', 'default');

		$as = $controller . '_';
		$uses = ($submodule) ? $submodule . '.' . $controller . '@' : $controller . '@';
		$uri = ($controller == 'default') ? '' : $controller;

		if ($submodule)
		{
			$as = $submodule . '_' . $as;
		}

		if ($bundle)
		{
			$as = $bundle . '_' . $as;
			$uses = $bundle . '::' . $uses;
			$uri = ($uri) ? $bundle . '/' . $uri : $bundle;
		}

		if ($submodule)
		{
			$uri = ($uri) ? $submodule . '/' . $uri : $submodule;
		}

		foreach ($this->actions as $action)
		{
			$this->$action($bundle, $controller, $uri, $as, $uses);
		}
	}

	protected function index($bundle, $controller, $uri, $as, $uses)
	{
		\Route::get(
			array(
				$uri,
				$uri . '.(json)',
				$uri . '.(ajax)'
			),
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__
			)
		);
	}

	protected function view($bundle, $controller, $uri, $as, $uses)
	{
		\Route::get(
			array(
				$uri . '/(:num)',
				$uri . '/(:num).(json)',
				$uri . '/(:num).(ajax)'
			),
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__
			)
		);
	}

	protected function add($bundle, $controller, $uri, $as, $uses)
	{
		\Route::get(
			array(
				$uri . '/add',
			),
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__
			)
		);
	}

	protected function edit($bundle, $controller, $uri, $as, $uses)
	{
		\Route::get(
			$uri . '/(:num)/edit',
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__
			)
		);
	}

	protected function create($bundle, $controller, $uri, $as, $uses)
	{
		\Route::post(
			$uri,
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__
			)
		);
	}

	protected function update($bundle, $controller, $uri, $as, $uses)
	{
		\Route::put(
			$uri . '/(:num)',
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__
			)
		);
	}

	protected function destroy($bundle, $controller, $uri, $as, $uses)
	{
		\Route::delete(
			$uri . '/(:num)',
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__
			)
		);
	}

	/**
	 * Список файлов
	 */
	protected function uploads($bundle, $controller, $uri, $as, $uses)
	{
		\Route::get(
			array(
				$uri . '/(:num)/uploads',
				$uri . '/(:num)/uploads.(json)',
				$uri . '/(:num)/uploads.(ajax)'
			),
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__
			)
		);
	}

	/**
	 * Получить файл
	 */
	protected function view_file($bundle, $controller, $uri, $as, $uses)
	{
		\Route::get(
			array(
				$uri . '/(:num)/uploads/(:num)',
				$uri . '/(:num)/uploads/(:num).(json)',
				$uri . '/(:num)/uploads/(:num).(ajax)'
			),
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__
			)
		);
	}

	/**
	 * Залить файл и привязать его к объекту
	 */
	protected function upload_file($bundle, $controller, $uri, $as, $uses)
	{
		\Route::post(
			$uri . '/(:num)/uploads',
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__
			)
		);
	}

	/**
	 * Удалить файл
	 */
	protected function destroy_file($bundle, $controller, $uri, $as, $uses)
	{
		\Route::delete(
			array(
				$uri . '/(:num)/uploads',
			),
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__
			)
		);
	}

	public function __call($method, $args)
	{
		$args = is_array($args[0]) ? $args[0] : $args;

		if ($method == 'except')
		{
			$this->actions = Arr::exceptValues($this->actions, $args);
			return $this;
		}

		if ($method == 'only')
		{
			$this->actions = Arr::onlyValues($this->actions, $args);
			return $this;
		}

		if ($method == 'with')
		{
			if (Arr::haveIntersections($args, array('file', 'files', 'img', 'image', 'images', 'uploads')))
			{
				$this->actions[] = 'uploads';
				$this->actions[] = 'upload_file';
				$this->actions[] = 'destroy_file';
				$this->actions[] = 'view_file';
			}

			return $this;
		}
	}
}
