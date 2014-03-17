<?php namespace Mobileka\L3\Engine\Grid\Components;

class Link extends BaseComponent {

	protected $template = 'engine::grid.link';
	protected $params   = array();
	protected $label    = '';
	protected $route    = '';
	protected $link;
	protected $query_params = array();

	public function label($value, $translate = false)
	{
		$this->label = $translate ? \Lang::findLine($translate, 'grid.' . $value) : $value;
		return $this;
	}

	public function getLink()
	{
		if (!is_null($this->link))
		{
			return $this->normalizeAttributeValue($this->link);
		}
		$url = \URL::to_route($this->route, $this->params);
		if ($this->query_params)
		{
			$url .= '?' . http_build_query($this->normalizeAttributeValue($this->query_params));
		}

		return $url;
	}
}
