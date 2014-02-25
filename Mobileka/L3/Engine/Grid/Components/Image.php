<?php namespace Mobileka\L3\Engine\Grid\Components;

class Image extends BaseComponent {

	protected $template = 'engine::grid.image_column';
	protected $imageAlias = 'admin_grid_thumb';
	protected $multi = true;
	protected $cropped = false;
	protected $featuredImage = false;

	public function featuredImage($field = '')
	{
		$this->featuredImage = $field ?: $this->name;
		return $this;
	}

	public function cropped($cropped = true)
	{
		$this->cropped = $cropped;
		return $this;
	}

	public function value($lang = '')
	{
		try
		{
			$relation = $this->name .'_uploads';

			if ($this->featuredImage)
			{
				$image = $this->row->belongs_to(\IoC::resolve('Uploader'), $this->name)
						->first() ?: $this->name;
			}
			else
			{
				$image = $this->row->{$relation}()->first() ?: $this->name;
			}

			return $this->row->getImageSrc($image, $this->imageAlias, $this->cropped);
		}
		catch(\Exception $e)
		{
			if (\Str::contains($e->getMessage(), $relation))
			{
				throw new \Exception(
					'Please, provide a "' . $relation . '" relation in your "' .
					get_class($this->row) . '"'
				);
			}

			throw $e;
		}
	}
}
