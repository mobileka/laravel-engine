<?php namespace Mobileka\L3\Engine\Form\Components;

class DropdownAjax extends Dropdown
{
    protected $template = 'engine::form.dropdown_ajax';
    protected $routes = array();
    protected $boundElement;

    public function options($options, $defaultValue = true)
    {
        $this->routes = array_values($options);
        $options = array_keys($options);

        return parent::options($options, $defaultValue);
    }
}
