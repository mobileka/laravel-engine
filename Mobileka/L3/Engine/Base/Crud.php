<?php namespace Mobileka\L3\Engine\Base;

use Mobileka\L3\Engine\Laravel\Helpers\Arr;
use Mobileka\L3\Engine\Laravel\Base\View;
use Mobileka\L3\Engine\Laravel\Lang;
use Exception;

abstract class Crud
{
    /**
     * An instance of a main model for a module / bundle
     * @var \Laravel\Database\Eloquent\Model
     */
    protected $model;

    /**
     * A template / view representing a Grid or a Form
     * @var string
     */
    protected $template;

    /**
     * Holds a unique ID of a current request ({bundle}_{controller}_{action})
     * @var string
     */
    protected $requestId;

    /**
     * @todo wtf?
     * @var array
     */
    protected $customData = array();

    /**
     * An array of components constituting a From / Grid
     * @var array
     */
    protected $components = array();

    /**
     * Build all configured components except stated in this array
     * @var array
     */
    protected $except = array();

    /**
     * Build only components stated in this array
     * @var array
     */
    protected $only = array();

    /**
     * An order of components
     * @var array
     */
    protected $order = array();

    /**
     * Path to a language file which will be used for translations
     * @var array
     */
    protected $languageFile = 'default';

    /**
     * Custom data passed to view when rendering the Crud. Note that the following
     * array keys are reserved: 'crud', 'components'.
     * @var array
     */
    protected $viewData = array();

    /**
     * Filters and sorts components
     *
     * @param  array $components
     * @param  array $order
     * @param  array $only
     * @param  array $except
     * @return array
     */
    public function processComponents($components, $order, $only, $except)
    {
        $components = Arr::onlyValues($components, $only);
        $components = Arr::exceptValues($components, $except);
        $components = Arr::sortByArray($components, $order);

        return $components;
    }

    /**
     * Renders a form or a grid
     *
     * @return \Laravel\View
     */
    public function render()
    {
        $viewData = array(
            'crud' => $this,
            'components' => $this->components
        ) + $this->viewData;

        return View::make($this->template, $viewData);
    }

    /**
     * Setter for the "model" property
     *
     * @param  \Eloquent                    $model
     * @return Mobileka\L3\Engine\Base\Crud
     */
    public function setModel(\Laravel\Database\Eloquent\Model $model)
    {
        $this->model = $model;

        return $this;
    }

    public function deleteConfirmationMessage($row = null)
    {
        $message = Lang::findLine($this->languageFile, 'sure');

        if (!$row or !($label = $row->getLabel())) {
            $message = str_replace(', что хотите удалить из базы :label', '', $message);
        } else {
            $message = str_replace(':label', $label, $message);
        }

        return $message;
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        throw new Exception("Trying to get an undefined property \"$property\" of a " . get_class($this) . " class");
    }
}
