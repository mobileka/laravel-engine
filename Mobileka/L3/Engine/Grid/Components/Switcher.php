<?php namespace Mobileka\L3\Engine\Grid\Components;

class Switcher extends BaseComponent {

	protected $template       = 'engine::grid.switcher';
	protected $route          = array();
	protected $skin           = 'square';
	protected $color          = 'blue';
	protected $uncheckedValue = 0;
	protected $checkedValue   = 1;
	protected $requiredAttributes = array('class' => 'icheck-me icheck-me-grid');
	protected static $skins   = array('minimal', 'square', 'flat');
	protected static $colors  = array('blue', 'red', 'green');

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

	public function __get($name)
	{
		if ($name === 'url')
		{
			return \URL::to_route(\Router::requestId($this->route) . '_update', array($this->row->id));
		}

		return parent::__get($name);
	}

}
