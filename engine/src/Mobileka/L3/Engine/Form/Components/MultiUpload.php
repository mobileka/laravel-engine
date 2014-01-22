<?php namespace Mobileka\L3\Engine\Form\Components;

class MultiUpload extends BaseComponent {

	protected $template = 'engine::form.multiupload';
	protected $featuredImageSelector = false;

	public function __get($name)
	{
		if ($name === 'files')
		{
			return $this->row->uploads;
		}

		return parent::__get($name);
	}

}