<?php namespace Mobileka\L3\Engine\Grid\Components;

use Mobileka\L3\Engine\Laravel\Helpers\Arr;
use Mobileka\L3\Engine\Laravel\Router;
use Mobileka\L3\Engine\Laravel\Lang;

/**
 * A component that allows to change a particular field right from the Grid
 */
class ActiveDropdown extends BaseComponent
{
    /**
     * A view which will be rendered by this component
     *
     * @var string
     */
    protected $template = 'engine::grid.active_dropdown';

    /**
     * A route alias which can handle the request
     *
     * @var string
     */
    protected $route;

    /**
     * Route parameters
     *
     * @var array
     */
    protected $params = array();

    /**
     * HTTP method of the request
     *
     * @var string - in: 'post', 'put', 'get', 'delete'
     */
    protected $method = 'PUT';

    /**
     * Required html attributes for the component
     *
     * @var array
     */
    protected $requiredAttributes = array('style' => 'width:115px;');

    /**
     * An array of dropdown options
     *
     * @var array
     */
    protected $options = array();

    /**
     * Get the dropdown name
     *
     * @return string
     */
    public function getName()
    {
        return Arr::getItem($this->attributes, 'multiple')
            ? $this->name . '[]'
            : $this->name
        ;
    }

    /**
     * Get HTTP method of the request
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route ? : Router::requestId(null, 'update');
    }

    /**
     * Get route parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params ? : array($this->row->id);
    }

    /**
     * Get a route alias which will handle the request
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set options for of dropdown
     *
     * @param  array          $options
     * @param  bool           $defaultValue
     * @return ActiveDropdown
     */
    public function options($options, $defaultValue = false)
    {
        if ($this->translate) {
            foreach ($options as $key => $option) {
                $options[$key] = Lang::findLine($this->languageFile, $option);
            }
        }

        if ($defaultValue) {
            $options = array(null => Lang::findLine($this->languageFile, 'not_selected')) + $options;
        }

        $this->options = $options;

        return $this;
    }

    /**
     * Returns a value of a component.
     *
     * @param  string $lang
     * @return mixed
     */
    public function value($lang = '')
    {
        $value = $this->row;

        if (Arr::getItem($this->attributes, 'multiple') === 'multiple') {
            $value = $value->{$this->name}()->lists('id');
        } else {
            $tokens = explode('.', $this->name);

            foreach ($tokens as $token) {
                $value = $value->{$token};
            }
        }

        return $value;
    }
}
