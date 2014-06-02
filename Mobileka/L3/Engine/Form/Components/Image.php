<?php namespace Mobileka\L3\Engine\Form\Components;

class Image extends BaseUploadComponent {
	protected $template = 'engine::form.image';
	protected $jcrop = array();

	public function jcrop($options = array())
	{
		if (is_array($options) and !$options)
		{
			$this->jcrop = true;
		}
		else
		{
			$this->jcrop = $options;
		}

		return $this;
	}

	public function value($lang = '')
	{
		if ($this->row->{$this->name})
		{
			return $this->row->getImageSrc($this->name, 'original', true);
		}

		$name = $this->name . '_uploads';

		if ($image = $this->row->{$name}()->first() and in_array(strtolower(\File::extension($image->path)), \Config::get('image.image_extensions')))
		{
			return $this->jcrop ? $image->croppedImage : $image->image;
		}

		return '';
	}
}
