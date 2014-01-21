<?php namespace Mobileka\L3\Engine\Form\Components;

class Dropdown extends BaseComponent {

	protected $template = 'crud::form.dropdown';
	protected $options = array();

	public function options($options, $defaultValue = true)
	{
		if ($this->translate)
		{
			foreach ($options as $key => $option)
			{
				$options[$key] = \Lang::findLine($this->languageFile, $option);
			}
		}

		if ($defaultValue)
		{
			$options = array(null => \Lang::findLine($this->languageFile, 'not_selected')) + $options;
		}

		$this->options = $options;

		return $this;
	}

	public function selectboxName()
	{
		return isset($this->attributes['multiple']) ? $this->name . '[]' : $this->name;
	}

	/**
	 * Returns a value of a component.
	 */
	public function value()
	{
		$value = $this->row;
		$tokens = explode('.', $this->name);

		foreach ($tokens as $token)
		{
			$value = $value->{$token};
		}

		return $value;
	}

}