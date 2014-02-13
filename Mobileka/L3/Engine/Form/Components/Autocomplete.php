<?php namespace Mobileka\L3\Engine\Form\Components;

class Autocomplete extends BaseComponent {

	protected $template = 'engine::form.autocomplete';

	protected $requiredAttributes = array(
		'data-items'   => 4,
		'data-provide' => 'typeahead',
		'data-source'  => array(),
		'autocomplete' => 'off',
	);

	public function __call($method, $args)
	{
		if (in_array($method, array('items', 'provide')))
		{
			$this->requiredAttributes['data-' . $method] = $args[0];
			return $this;
		}

		if ($method === 'source')
		{
			$this->requiredAttributes['data-source'] = json_encode($args[0]);
			return $this;
		}

		return parent::__call($method, $args);
	}

}
