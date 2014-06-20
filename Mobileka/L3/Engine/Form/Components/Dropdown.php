<?php namespace Mobileka\L3\Engine\Form\Components;

use Mobileka\L3\Engine\Laravel\Lang;
use Mobileka\L3\Engine\Laravel\Helpers\Arr;

class Dropdown extends BaseComponent
{
    protected $template = 'engine::form.dropdown';
    protected $htmlElement = 'select';
    protected $options = array();

    public function options($options, $defaultValue = true)
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

    public function selectboxName()
    {
        return Arr::getItem($this->attributes, 'multiple') ? $this->name . '[]' : $this->name;
    }

    /**
     * Returns a value of a component.
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
