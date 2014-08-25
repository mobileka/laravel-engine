<?php namespace Mobileka\L3\Engine\Base;

use Mobileka\L3\Engine\Laravel\Helpers\Arr;
use Mobileka\L3\Engine\Laravel\Base\View;
use Mobileka\L3\Engine\Laravel\Str;
use Mobileka\L3\Engine\Laravel\HTML;
use Laravel\IoC;

use Mobileka\L3\Engine\Laravel\Lang;

/**
 * Components represent majority of functionality needed to build forms and
 * grids. In case of a form, each component renders an element (textfield,
 * checkbox, etc) to manipulate model's value. In grids, components refer to
 * columns used to display model values.
 */
abstract class Component
{
    /**
     * A name of a component.
     * Typically used in input[name]
     * @var string
     */
    protected $name;

    /**
     * Do we need to escape the output of the Component?
     * @var bool
     */
    protected $escape = true;

    /**
     * Do we need to nl2br the output of the Component?
     * @var bool
     */
    protected $nl2br = true;

    /**
     * Do we need to purify the output of the Component?
     * @var bool
     */
    protected $purify = false;

    /**
     * Holds an instance of the HTMLPurifier class
     * @var null|HTMLPurifier
     */
    protected $purifier = null;

    /**
     * A value before limit words and characters
     * @var string
     */
    protected $rawValue = null;

    /**
     * Limit characters to specified amount
     * @var int
     */
    protected $limitCharacters = null;

    /**
     * Limit words to specified amount
     * @var int
     */
    protected $limitWords = null;

    /**
     * Type of a HTML element
     * @var string
     */
    protected $htmlElement = 'text';

    /**
     * A name of a template (view) representing a component
     * @var string
     */
    protected $template;

    /**
     * An array of html attributes
     * @var array
     */
    protected $attributes = array();

    /**
     * A class that will be added to the component's parent
     * @var string
     */
    protected $parentClass = '';

    /**
     * A set of possible JavaScript validation rules
     * @var array
     */
    protected static $validationRules = array(
        'required',
        'remote',
        'email',
        'url',
        'date',
        'dateISO',
        'number',
        'digits',
        'creditcard',
        'equalTo',
        'accept',
        'maxlength',
        'minlength',
        'rangelength',
        'range',
        'max',
        'min',
    );

    /**
     * If you need the component to be shown in a limited set of actions,
     * enumerate these actions here
     * @var array
     */
    protected $relevantActions = array();

    /**
     * Is i18n enabled for this component?
     * @var bool
     */
    protected $localized = false;

    /**
     * If set to true, a value will be used as a key for a language file
     * @var bool
     */
    protected $translate = false;

    /**
     * Language file which will serve as a source of translations
     * @var string
     */
    protected $languageFile = 'default';

    /**
     * A database row bound to a component
     * @var Eloquent
     */
    protected $row;

    protected $value;

    /**
     * Manually set html attributes for a component
     * @var array
     */
    protected $requiredAttributes = array();

    /**
     * Show or hide the whole component in the form.
     * @var boolean
     */
    protected $active = true;

    public static function make($name, $attributes = array())
    {
        $self = new static;
        $self->name = $name;
        $self->attributes = $attributes;

        return $self;
    }

    protected function normalizeAttributeValue($value)
    {
        if ($value instanceof \Closure) {
            return $this->rawValue = call_user_func($value, $this->row, $this);
        }

        return $value;
    }

    protected function purify($value)
    {
        if ($config = $this->purify) {
            $this->purifier = $this->purifier ? : IoC::resolve('Purifier', $config);
            $value = $this->purifier->purify($value);
        }

        return $value;
    }

    protected function escape($value)
    {
        if ($this->escape and !$this->purify) {
            $value = HTML::entities($value);
        }

        return $value;
    }

    protected function nl2br($value)
    {
        if ($this->nl2br and !$this->purify) {
            $value = nl2br($value);
        }

        return $value;
    }

    /**
     * Returns a value of a component
     *
     * @return mixed
     */
    public function value($lang = '')
    {
        if (!is_null($this->value)) {
            return $this->normalizeAttributeValue($this->value);
        }

        $value = $this->row;

        $tokens = explode('.', $this->name);


        for ($i = 0, $count = count($tokens); $i < $count; $i++) {
            if ($this->localized and $i == ($count - 1)) {
                $value = $value->localized($tokens[$i], $lang);
            } elseif ($value) {
                $value = $value->{$tokens[$i]};
            }
        }

        $this->rawValue = $value;
        $value = !is_null($limit = $this->limitCharacters) ? Str::limitCharacters($value, $limit) : $value;
        $value = !is_null($limit = $this->limitWords) ? Str::limitWords($value, $limit) : $value;
        $value = ($this->translate) ? Lang::findLine($this->languageFile, $value) : $value;

        $value = $this->purify($value);

        if (!is_array($value))
        {
            $value = $this->escape($value);
            $value = $this->nl2br($value);
        }

        return $value;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Translate a value of a component
     *
     * @param  string    $languageFile
     * @return Component
     */
    public function translate($languageFile = '')
    {
        $this->translate = true;
        $this->languageFile = ($languageFile) ? : $this->languageFile;

        return $this;
    }

    /**
     * If component is hidden, it won't be shown in a template
     * Useful for hidden form fields which should exist but shouldn't be shown
     * @param bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Render a template (view / $this->template) bound to a component
     *
     * @return \Laravel\View
     */
    public function render($lang = '')
    {
        $name = $lang ? 'localized['.$lang.']['. $this->name .']' : $this->name;
        $inputOldName = $lang ? 'localized.'.$lang.'.'.$this->name : $name;

        foreach ($this->requiredAttributes as $key => $value) {
            if ($attr = Arr::getItem($this->attributes, $key) and $attr !== $value) {
                $this->attributes[$key] .= " $value";
            } else {
                $this->attributes[$key] = $value;
            }
        }

        return View::make(
            $this->template,
            array(
                'lang' => $lang,
                'name' => $name,
                'inputOldName' => $inputOldName,
                'component' => $this
            )
        );
    }

    /**
     * Render a view with a "star" if a field is required
     *
     * @param  null | string $view - a view to render. engine::form._star by default
     * @return string
     */
    public function required($view = null)
    {
        $view = ($view) ?: 'engine::form._star';

        $result = '';

        if ($this->row) {
            $rules = $this->localized
                ? Arr::searchRecursively($this->row->translatable, 'rules', $this->name, '')
                : Arr::getItem($this->row->rules, $this->name, '')
            ;

            if (Str::contains($rules, 'required')) {
                $result = View::make($view);
            }
        }

        return $result;
    }

    /**
     * Adds data attributes to form fields according to the validation rules
     * This is needed for JavaScript validation
     *
     * @param  array | string $rules
     * @return Component
     */
    public function validate($rules)
    {
        $rules = is_array($rules) ? $rules : array($rules);

        foreach ($rules as $rule) {
            if (!in_array($rule, static::$validationRules)) {
                throw new \Exception("Invalid Validation rule: $rule");
            }

            $this->attributes['data-rule-' . $rule] = 'true';
        }

        return $this;
    }

    public function __call($method, $args)
    {
        if (property_exists($this, $method)) {
            $this->{$method} = $args[0];

            return $this;
        }

        throw new \Exception("Call to undefined method $method of a " . get_class($this) . ' class');
    }

    public function __get($property)
    {
        $method = 'get' . ucfirst($property);
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        if (property_exists($this, $property)) {
            return $this->normalizeAttributeValue($this->$property);
        }

        throw new \Exception("Trying to get an undefined property \"$property\" of a " . get_class($this) . " class");
    }
}
