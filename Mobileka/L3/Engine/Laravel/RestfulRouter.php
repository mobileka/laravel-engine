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

	public $https;
	public $csrf = true;

	public static function make()
	{
		$https = Config::get('application.ssl', true);
		return new static;
	}

	public function resource(array $options)
	{
		$submodule = Arr::getItem($options, 'submodule', '');
		$uriPrefix = $submodule == 'admin' ? admin_uri() : $submodule;
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

		if ($uriPrefix)
		{
			$uri = ($uri) ? $uriPrefix . '/' . $uri : $uriPrefix;
		}

		foreach ($this->actions as $action)
		{
			$this->$action($bundle, $controller, $uri, $as, $uses, $https = null);
		}
	}

	protected function index($bundle, $controller, $uri, $as, $uses, $https = null)
	{
		\Route::get(
			array(
				$uri,
				$uri . '.(json)',
				$uri . '.(ajax)'
			),
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__,
				'https' => is_null($https) ? $this->https : $https
			)
		);
	}

	protected function view($bundle, $controller, $uri, $as, $uses, $https = null)
	{
		\Route::get(
			array(
				$uri . '/(:num)',
				$uri . '/(:num).(json)',
				$uri . '/(:num).(ajax)'
			),
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__,
				'https' => is_null($https) ? $this->https : $https
			)
		);
	}

	protected function add($bundle, $controller, $uri, $as, $uses, $https = null)
	{
		\Route::get(
			$uri . '/add',
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__,
				'https' => is_null($https) ? $this->https : $https
			)
		);
	}

	protected function edit($bundle, $controller, $uri, $as, $uses, $https = null)
	{
		\Route::get(
			$uri . '/(:num)/edit',
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__,
				'https' => is_null($https) ? $this->https : $https
			)
		);
	}

	protected function create($bundle, $controller, $uri, $as, $uses, $https = null)
	{
		\Route::post(
			$uri,
			$this->generateAction($as, __FUNCTION__, $uses, $https = null)
		);
	}

	protected function update($bundle, $controller, $uri, $as, $uses, $https = null)
	{
		\Route::put(
			$uri . '/(:num)',
			$this->generateAction($as, __FUNCTION__, $uses, $https = null)
		);
	}

	protected function destroy($bundle, $controller, $uri, $as, $uses, $https = null)
	{
		\Route::delete(
			$uri . '/(:num)',
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__,
				'https' => is_null($https) ? $this->https : $https
			)
		);
	}

	/**
	 * Список файлов
	 */
	protected function uploads($bundle, $controller, $uri, $as, $uses, $https = null)
	{
		\Route::get(
			array(
				$uri . '/(:num)/uploads',
				$uri . '/(:num)/uploads.(json)',
				$uri . '/(:num)/uploads.(ajax)'
			),
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__,
				'https' => is_null($https) ? $this->https : $https
			)
		);
	}

	/**
	 * Получить файл
	 */
	protected function view_file($bundle, $controller, $uri, $as, $uses, $https = null)
	{
		\Route::get(
			array(
				$uri . '/(:num)/uploads/(:num)',
				$uri . '/(:num)/uploads/(:num).(json)',
				$uri . '/(:num)/uploads/(:num).(ajax)'
			),
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__,
				'https' => is_null($https) ? $this->https : $https
			)
		);
	}

	/**
	 * Залить файл и привязать его к объекту
	 */
	protected function upload_file($bundle, $controller, $uri, $as, $uses, $https = null)
	{
		\Route::post(
			$uri . '/(:num)/uploads',
			$this->generateAction($as, __FUNCTION__, $uses, $https = null)
		);
	}

	/**
	 * Удалить файл
	 */
	protected function destroy_file($bundle, $controller, $uri, $as, $uses, $https = null)
	{
		\Route::delete(
			$uri . '/(:num)/uploads',
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__,
				'https' => is_null($https) ? $this->https : $https
			)
		);
	}

	protected function download($bundle, $controller, $uri, $as, $uses, $https = null)
	{
		\Route::get(
			$uri . '/(:num)/download/(:any)',
			array(
				'as' => $as . __FUNCTION__,
				'uses' => $uses . __FUNCTION__,
			)
		);
	}

	protected function generateAction($alias, $action, $uses, $https = null)
	{
		$result = array(
			'as' => $alias . $action,
			'uses' => $uses . $action,
			'https' => is_null($https) ? $this->https : $https
		);

		if ($this->csrf)
		{
			$result['before'] = 'csrf';
		}

		return $result;
	}

	public function __call($method, $args)
	{
		$args = is_array($args[0]) ? $args[0] : $args;

		if ($method == 'csrf')
		{
			$this->csrf = $args[0];
			return $this;
		}

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

			if (Arr::haveIntersections($args, array('download')))
			{
				$this->actions[] = 'download';
			}

			return $this;
		}
	}

	public static function __callStatic($method, $args)
	{
		if (in_array($method, array('get', 'post', 'put', 'delete', 'any')))
		{
			$args['https'] = Arr::getItem($args, 'https', true);
			return forward_static_call_array(array('Route', $method), $args);
		}

		throw new \Exception("Trying to call an undefined static method $method of a ".get_called_class()." class");
	}
}
