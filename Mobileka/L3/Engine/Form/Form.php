<?php namespace Mobileka\L3\Engine\Form;

use \Helpers\Misc,
	\Helpers\Arr;

/**
 * Form class of a CRUD bundle
 *
 * @author Armen Markossyan <a.a.markossyan@gmail.com>
 * @version 1.0
 */

class Form extends \Mobileka\L3\Engine\Base\Crud {

	protected $template = 'engine::form';
	protected $rules = array();
	protected $actionUrl, $method, $cancelUrl, $successUrl;
	protected $urls = array();

	protected $attributes = array(
		'class' => 'form-horizontal form-bordered form-wysiwyg form-validate',
		'id'    => 'form_validate'
	);

	public static function make(\Eloquent $model, $config = array())
	{
		return new static($model, $config);
	}

	public function __construct(\Eloquent $model, $config = array())
	{
		$this->model = $model;
		$this->rules = $model->rules;
		$this->requestId = \Router::requestId(\Controller::$route);
		$this->attributes['data-alias'] = \Controller::$route['alias'];

		foreach ($config as $key => $item)
		{
			if (!property_exists($this, $key))
			{
				throw new \Exception("Trying to configure an unexisting Form property. Please check your config file for \"$key\" key");
			}
			elseif (!in_array($key, array('action', 'method', 'cancelUrl', 'successUrl')))
			{
				$this->{$key} = $item;
			}
		}

		$this->components = $this->processComponents($this->components, $this->order, $this->only, $this->except);

		/*list($this->action, $this->method, $this->cancelUrl, $this->successUrl) = $this->detectUrls($model);
		$this->action = $this->setUrl('action', $config);
		$this->method = $this->setMethod($config);
		$this->cancelUrl = $this->setUrl('cancelUrl', $config);
		$this->successUrl = $this->setUrl('successUrl', $config);*/
	}

	public function setActionUrls($action = 'any', $urls = array())
	{
		$this->urls = array($action => $urls);
		return $this;
	}

	protected function detectUrls($model)
	{
		list($actionUrl, $method, $successUrl, $cancelUrl) = $this->detectDefaultUrls($model);
		$action = \Controller::$route['action'];

		if ($action = Arr::getItem($this->urls, $action) or $action = Arr::getItem($this->urls, 'any'))
		{
			$actionUrl = Arr::getItem($action, 'actionUrl') ? : $actionUrl;
			$method = Arr::getItem($action, 'method') ? : $method;
			$successUrl = Arr::getItem($action, 'successUrl') ? : $successUrl;;
			$cancelUrl = Arr::getItem($action, 'cancelUrl') ? : $cancelUrl;;
		}

		return array($actionUrl, $method, $cancelUrl, $successUrl);
	}

	/**
	 * Sets action, method, successUrl and cancelUrl according to a convention
	 *
	 * @param Eloquent $model
	 * @return array
	 */
	protected function detectDefaultUrls($model)
	{
		$route = \Controller::$route;
		$params = array();

		/**
		 * @todo describe a convention here
		 */
		$controller = $route['bundle'] ? $route['bundle'] . '_' . $route['controller'] : $route['controller'];
		$controller = str_replace('.', '_', $controller);

		/**
		 * by default, any form in case of success or cancel will redirect
		 * to an index action of a current bundle::controller
		*/
		$cancelUrl = \Router::has($controller . '_index') ? \URL::to_route($controller . '_index') : null;

		/**
		 * If model exists then we are updating it (update action)
		 */
		if ($model->exists)
		{
			$method = 'PUT';
			$alias = $controller . '_update';
			$params = array('id' => $model->id);
		}
		/**
		 * "Create" action otherwise
		 */
		else
		{
			$method = 'POST';
			$alias = $controller . '_create';
		}

		$action = \Router::has($alias, $method) ? \URL::to_route($alias, $params) : null;

		/**
		 * The last $cancelUrl is a successUrl
		 * because in a default scenario these are the same
		 */
		return array($action, $method, $cancelUrl, $cancelUrl);
	}

	/**
	 * Parses a method from a config file
	 *
	 * @param array $config
	 * @return string
	 */
	public function setMethod($config)
	{
		$method = $this->method;

		if ($config = Arr::getItem($config, 'action'))
		{
			/**
			 * Set a method if specified
			 */
			$method = Arr::getItem($config, 'method', $method);
		}

		return $method;
	}

	/**
	 * Parses a url from config file for different things
	 *
	 * @param string $type: action, createUrl, successUrl
	 * @param array $config
	 * @return string
	 */
	public function setUrl($type, $config)
	{
		/**
		 * @todo it is better to pass a default value
		 * instead of doing this
		 */
		$result = $this->{$type};

		if ($config = Arr::getItem($config, $type))
		{
			/**
			 * Params of a route
			 * This can be specified for urlToRoute or urlToAction
			 */
			$params = Arr::getItem($config, 'params', array());

			/**
			 * Set cancel url if specified
			 */
			$result = Arr::getItem($config, 'url', $result);

			/**
			 * If specified, set cancel url with \URL::to_action()
			 * This has a higher priority than 'url' config parameter
			 */
			if ($urlToAction = Arr::getItem($config, 'urlToAction'))
			{
				$result = \URL::to_action($urlToAction, $params);
			}

			/**
			 * If specified, set cancel url by route alias
			 * This has a higher priority than 'url' and 'urlToAction' config parameters
			 */
			if ($alias = Arr::getItem($config, 'urlToRoute'))
			{
				$result = \URL::to_route($alias, $params);
			}

			if ($queryString = Arr::getItem($config, 'queryString'))
			{
				$result .= '?' . $queryString;
			}
		}

		return $result;
	}

	/**
	 * Renders a form or a grid
	 *
	 * @return \Laravel\View
	 */
	public function render()
	{
		list($this->actionUrl, $this->method, $this->cancelUrl, $this->successUrl) = $this->detectUrls($this->model);
		return parent::render();
	}
}
