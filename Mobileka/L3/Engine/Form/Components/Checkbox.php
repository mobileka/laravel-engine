<?php namespace Mobileka\L3\Engine\Form\Components;

class Checkbox extends BaseComponent {

	protected $template = 'engine::form.checkbox';
	protected $htmlElement = 'checkbox';

	protected static $skins   = array('minimal', 'square', 'flat');
	protected static $colors  = array('blue', 'red', 'green');

	protected $skin           = 'square';
	protected $color          = 'blue';
	protected $uncheckedValue = 0;
	protected $checkedValue   = 1;

	public function __call($name, $args)
	{
		if ($name === 'skin')
		{
			if (!in_array($args[0], static::$skins))
			{
				throw new \Exception("Unacceptable value of the skin property: {$args[0]}");
			}
		}

		if ($name === 'color')
		{
			if (!in_array($args[0], static::$colors))
			{
				throw new \Exception("Unacceptable value of the color property: {$args[0]}");
			}
		}

		return parent::__call($name, $args);
	}

}
