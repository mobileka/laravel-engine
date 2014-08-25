<?php namespace Mobileka\L3\Engine\Form\Components;

use Mobileka\L3\Engine\Laravel\Date,
	Mobileka\L3\Engine\Laravel\File;

class SimpleFile extends BaseComponent {

	protected $template = 'engine::form.simple_file';
	protected $alias = 'admin_simple_file_thumb';

	public function value($lang = '')
	{
		$filename = $this->row->{$this->name};
		$path = $this->row->simpleFilePath . '/' . $filename;

		if ($filename and is_file($path))
		{
			return \Laravel\File::is(array('jpg', 'png', 'gif'), $path) ? $this->row->{$this->name}($this->alias) : File::getExtensionIcon($path);
		}

		return dummyThumbnail();
	}

	public function url()
	{
		$filename = $this->row->{$this->name};
		$path = $this->row->simpleFilePath . '/' . $filename;

		if ($filename and is_file($path))
		{
			return $this->row->{$this->name}($this->alias);
		}

		return dummyThumbnail();
	}
}