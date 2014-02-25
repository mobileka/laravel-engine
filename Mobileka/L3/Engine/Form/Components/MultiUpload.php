<?php namespace Mobileka\L3\Engine\Form\Components;

class MultiUpload extends BaseUploadComponent {

	protected $template = 'engine::form.multiupload';
	protected $featuredImageSelector = false;

	public function featuredImageSelector($fieldName = null)
	{
		$this->featuredImageSelector = $fieldName ? : $this->name;
		return $this;
	}

	public function __get($name)
	{
		if (in_array($name, array('files', 'uploads', 'images')))
		{
			$relation = $this->name . '_uploads';
			return $this->row->{$relation};
		}

		return parent::__get($name);
	}
}
