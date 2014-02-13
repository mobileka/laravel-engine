<?php namespace Mobileka\L3\Engine\Grid\Components;

class ImageColumn extends BaseComponent {

	protected $template = 'engine::grid.image_column';
	protected $imageAlias = 'admin_grid_thumb';
	protected $multi = true;
	protected $cropped = true;

	public function single($single = true)
	{
		$this->multi = !$single;
		return $this;
	}

	public function value()
	{
		if ($this->multi)
		{
			return ($image = $this->row->image()->first())
				? $image->{$this->imageAlias}
				: dummyThumbnail($this->imageAlias)
			;
		}

		$val = parent::value();

		return (is_string($val) and \Str::contains($val, 'http://'))
			? $val
			: $this->row->getImageSrc($this->name, $this->imageAlias, $this->cropped)
		;
	}
}
