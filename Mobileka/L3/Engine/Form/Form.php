<?php namespace Mobileka\L3\Engine\Form;

use Mobileka\L3\Engine\Laravel\Helpers\Arr;
use Mobileka\L3\Engine\Laravel\Router;
use Mobileka\L3\Engine\Laravel\URL;
use Mobileka\L3\Engine\Laravel\Base\Controller;

/**
 * CRUD form generation class
 *
 * @author Armen Markossyan <a.a.markossyan@gmail.com>
 * @version 1.0
 */

class Form extends \Mobileka\L3\Engine\Base\Crud
{
    protected $template = 'engine::form';
    protected $rules = array();
    protected $actionUrl;
    protected $method;
    protected $cancelUrl;
    protected $successUrl;
    protected $urls = array();

    protected $attributes = array(
        'class' => 'form-horizontal form-bordered form-wysiwyg form-validate',
        'id' => 'form_validate'
    );

    public static function make($model, $config = array())
    {
        return new static($model, $config);
    }

    public function __construct($model, $config = array())
    {
        $this->model = $model;
        $this->rules = $model->rules;
        $this->requestId = Router::requestId(Controller::$route);
        $this->attributes['data-alias'] = Controller::$route['alias'];

        foreach ($config as $key => $item) {
            if (!property_exists($this, $key)) {
                throw new \Exception("Trying to configure an unexisting Form property. Please check your config file for \"$key\" key");
            } elseif (!in_array($key, array('action', 'method', 'cancelUrl', 'successUrl'))) {
                $this->{$key} = $item;
            }
        }

        $this->components = $this->processComponents($this->components, $this->order, $this->only, $this->except);
    }

    /**
     * Customize URLs such as form action, successUrl and cancelUrl
     *
     * Usage: ->setActionUrls('edit', ['cancelUrl' => 'http://example.com', 'actionUrl' => '...', 'method' => 'POST'])
     *
     * @param  string                       $action
     * @param  array                        $urls
     * @return Mobileka\L3\Engine\Form\Form
     */
    public function setActionUrls($action = 'any', $urls = array())
    {
        $this->urls = array($action => $urls);

        return $this;
    }

    protected function detectUrls($model)
    {
        $action = Controller::$route['action'];
        list($actionUrl, $method, $successUrl, $cancelUrl) = $this->detectDefaultUrls($model, $action);

        if ($action = Arr::getItem($this->urls, $action) or $action = Arr::getItem($this->urls, 'any')) {
            $actionUrl = Arr::getItem($action, 'actionUrl') ? : $actionUrl;
            $method = Arr::getItem($action, 'method') ? : $method;
            $successUrl = Arr::getItem($action, 'successUrl') ? : $successUrl;
            $cancelUrl = Arr::getItem($action, 'cancelUrl') ? : $cancelUrl;
        }

        return array($actionUrl, $method, $cancelUrl, $successUrl);
    }

    /**
     * Sets action, method, successUrl and cancelUrl according to a convention
     *
     * @param  Eloquent $model
     * @return array
     */
    protected function detectDefaultUrls($model, $action)
    {
        $route = Controller::$route;
        $params = array();

        /**
         * @todo describe the convention here
         */
        $controller = $route['bundle'] ? $route['bundle'] . '_' . $route['controller'] : $route['controller'];
        $controller = str_replace('.', '_', $controller);

        /**
         * by default, any form in case of success or cancel will redirect
         * to an index action of a current bundle::controller
        */
        $cancelUrl = Router::has($controller . '_index') ? URL::to_route($controller . '_index') : null;

        /**
         * If model exists then we are updating or cloning it
         */
        if ($model->exists and $action !== 'clone') {
            $params = array('id' => $model->id);
            $method = 'PUT';
            $alias = $controller . '_update';
        } else { // "Create" action otherwise
            $method = 'POST';
            $alias = $controller . '_create';
        }

        $action = Router::has($alias, $method) ? URL::to_route($alias, $params) : null;

        // In a typical situation $cancelUrl and $successUrl are identical
        return array($action, $method, $cancelUrl, $cancelUrl);
    }

    /**
     * Parses a method from a config file
     *
     * @param  array  $config
     * @return string
     */
    public function setMethod($config)
    {
        $method = $this->method;

        if ($config = Arr::getItem($config, 'action')) {
            // Set a method if specified
            $method = Arr::getItem($config, 'method', $method);
        }

        return $method;
    }

    /**
     * Parses a url from config file for different things
     *
     * @param  string $type:  action, createUrl, successUrl
     * @param  array  $config
     * @return string
     */
    public function setUrl($type, $config)
    {
        /**
         * @todo it is better to pass a default value
         * instead of doing this
         */
        $result = $this->{$type};

        if ($config = Arr::getItem($config, $type)) {
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
             * If specified, set cancel url with URL::to_action()
             * This has a higher priority than 'url' config parameter
             */
            if ($urlToAction = Arr::getItem($config, 'urlToAction')) {
                $result = URL::to_action($urlToAction, $params);
            }

            /**
             * If specified, set cancel url by route alias
             * This has a higher priority than 'url' and 'urlToAction' config parameters
             */
            if ($alias = Arr::getItem($config, 'urlToRoute')) {
                $result = URL::to_route($alias, $params);
            }

            if ($queryString = Arr::getItem($config, 'queryString')) {
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
