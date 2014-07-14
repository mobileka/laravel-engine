<?php namespace Mobileka\L3\Engine\Grid\Components;

use Mobileka\L3\Engine\Laravel\URL;

class Link extends BaseComponent
{
    protected $template = 'engine::grid.link';
    protected $params   = array();
    protected $label = '';
    protected $beforeLabel = '';
    protected $afterLabel = '';
    protected $route    = '';
    protected $link;
    protected $query_params = array();

    public function getLabel()
    {
        if (!is_null($this->label)) {
            return $this->normalizeAttributeValue($this->label);
        }

        return $this->row->{$this->name};
    }

    public function getParams()
    {
        if (!is_null($this->params)) {
            return $this->normalizeAttributeValue($this->params);
        }

        return array();
    }

    public function getLink()
    {
        if (!is_null($this->link)) {
            return $this->normalizeAttributeValue($this->link);
        }

        $url = URL::to_route($this->route, $this->getParams());

        if ($this->query_params) {
            $url .= '?' . http_build_query($this->normalizeAttributeValue($this->query_params));
        }

        return $url;
    }
}
