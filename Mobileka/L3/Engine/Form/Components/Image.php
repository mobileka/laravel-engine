<?php namespace Mobileka\L3\Engine\Form\Components;

class Image extends BaseUploadComponent {
	protected $template = 'engine::form.image';
	protected $jcrop = array();

	public function value($lang = '')
	{
		if ($this->row->{$this->name})
		{
			return $this->row->getImageSrc($this->name, 'original', true);
		}

		$name = $this->name . '_uploads';

		if ($image = $this->row->{$name}()->first())
		{
			$image = $this->jcrop ? $image->croppedImage : $image->image;
		}

		return $image;
	}
}
