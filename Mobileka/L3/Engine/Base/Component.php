<?php namespace Mobileka\L3\Engine\Base;

use Mobileka\L3\Engine\Laravel\Helpers\Arr,
	Mobileka\L3\Engine\Laravel\Base\View,
	\Str;

abstract class Component {

	/**
	 * A name of a component.
	 * Typically used in input[name]
	 * @var string
	 */
	protected $name;

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
	 * @todo: explain this shit
	 */
	protected $relevantActions = array();

	/**
	 * Is i18n enabled for this component?
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

	/**
	 * Manually set html attributes for a component
	 * @var array
	 */
	protected $requiredAttributes = array();

	public static function make($name, $attributes = array())
	{
		$self = new static;
		$self->name = $name;
		$self->attributes = $attributes;
		return $self;
	}

	/**
	 * Returns a value of a component
	 *
	 * @return mixed
	 */
	public function value($lang = '')
	{
		$value = $this->row;

		if ($this->localized)
		{
			$value = $this->row->localized($this->name, $lang);
		}
		else
		{
			$tokens = explode('.', $this->name);

			foreach ($tokens as $token)
			{
				$value = $value->{$token};
			}
		}

		return ($this->translate) ? \Lang::findLine($this->languageFile, $value) : $value;
	}

	/**
	 * Translate a value of a component
	 *
	 * @param string $languageFile
	 * @return Component
	 */
	public function translate($languageFile = '')
	{
		$this->translate = true;
		$this->languageFile = ($languageFile) ? : $this->languageFile;
		return $this;
	}

	/**
	 * This method is used to convert dots in component name to double underscores.
	 * A string before a dot represents a relation name and a string after __ a name of an attribute of this relation.
	 *
	 * This hack is needed to make it possible to save related models automatically.
	 *
	 * @see Haven't tested yet, use at your own risk
	 * @return string
	 */
	public function name()
	{
		return str_replace('.', '__', $this->name);
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

		foreach ($this->requiredAttributes as $key => $value)
		{
			if ($attr = Arr::getItem($this->attributes, $key) and $attr !== $value)
			{
				$this->attributes[$key] .= " $value";
			}
			else
			{
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
	 * @param null | string $view - a view to render. engine::form._star by default
	 * @return string
	 */
	public function required($view = null)
	{
		$view = ($view) ?: 'engine::form._star';

		$result = '';

		if ($this->row)
		{
			$rules = $this->localized
				? Arr::searchRecursively($this->row->translatable, 'rules', $this->name, '')
				: Arr::getItem($this->row->rules, $this->name, '')
			;

			if (Str::contains($rules, 'required'))
			{
				$result = \View::make($view);
			}
		}

		return $result;
	}

	/**
	 * Adds data attributes to form fields according to the validation rules
	 * This is needed for JavaScript validation
	 *
	 * @param array | string $rules
	 * @return Component
	 */
	public function validate($rules)
	{
		$rules = is_array($rules) ? $rules : array($rules);

		foreach ($rules as $rule)
		{
			if (!in_array($rule, static::$validationRules))
			{
				throw new \Exception("Invalid Validation rule: $rule");
			}

			$this->attributes['data-rule-' . $rule] = 'true';
		}

		return $this;
	}

	public function __call($method, $args)
	{
		if (property_exists($this, $method))
		{
			$this->{$method} = $args[0];
			return $this;
		}

		throw new \Exception("Call to undefined method $method of a " . get_class($this) . ' class');
	}

	public function __get($property)
	{
		if ($property === 'name')
		{
			return $this->name();
		}

		if (property_exists($this, $property))
		{
			return $this->{$property};
		}

		throw new \Exception("Trying to get an undefined property \"$property\" of a " . get_class($this) . " class");
	}
}
