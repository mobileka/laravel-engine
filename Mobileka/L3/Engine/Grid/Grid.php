<?php namespace Mobileka\L3\Engine\Grid;

use Mobileka\L3\Engine\Laravel\Helpers\Arr;
use Mobileka\L3\Engine\Laravel\Base\Controller;
use Router;
use Exception;

class Grid extends \Mobileka\L3\Engine\Base\Crud
{
    protected $template = 'engine::grid';
    protected $items = array();
    protected $filters = array();
    protected $sortable = array();
    protected $unsortable = array();

    public static function make($model, $config = array(), $items = array())
    {
        return new static($model, $config, $items);
    }

    public function __construct($model, $config = array(), $items = array())
    {
        $this->model = $model;
        $this->items = $items;
        $this->requestId = Router::requestId(Controller::$route);

        foreach ($config as $key => $item) {
            if (property_exists($this, $key)) {
                $this->{$key} = $item;
            } else {
                throw new Exception("Trying to configure an unexisting Grid property. Please check your config file for \"$key\" key");
            }
        }

        $this->sortable = $this->sortable ? : array_keys($this->components);
        $this->sortable = Arr::exceptValues($this->sortable, $this->unsortable);
        $this->components = $this->processComponents($this->components, $this->order, $this->only, $this->except);
    }

    /**
     * Setter for the "items" property
     *
     * @param  mixed                         $items
     * @return Mobileka\L3\Engine\Grid\Grid;
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }
}
