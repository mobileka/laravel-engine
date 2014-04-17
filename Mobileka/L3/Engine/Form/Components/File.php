<?php namespace Mobileka\L3\Engine\Form\Components;

class File extends Image {
	protected $template = 'engine::form.file';

	public function value($lang = '')
	{
		if ($this->row->{$this->name})
		{
			$route           = \Controller::$route;
			$route['action'] = 'download';
			$route           = \Router::requestId($route, true);

			return \HTML::link_to_route($route, \File::extension($this->row->{$this->name}), array($this->row->id, $this->name));
		}

		return ___('default', 'labels.no_file');
	}
}
