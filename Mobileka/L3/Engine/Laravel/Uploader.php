<?php namespace Mobileka\L3\Engine\Laravel;

use	Mobileka\L3\Engine\Laravel\Helpers\Arr;
use Mobileka\L3\Engine\Laravel\Base\Model;

class Uploader extends Model {

	public static $table = 'uploads';
	public static $rules = array('object_id' => 'integer');
	public static $template = 'engine::form.thumbnail_view';

	public function get_template()
	{
		return static::$template;
	}

	public function to_array()
	{
		$this->url = $this->get_url();
		$this->path = $this->get_path();
		return parent::to_array();
	}

	public function get_croppedImage()
	{
		return image('crop_' . $this->filename, $this->type, $this->created_at);
	}

	public function get_path()
	{
		return $this->isImage() ? $this->imagePath : $this->documentPath;
	}

	public function get_url()
	{
		return $this->isImage() ? $this->image : $this->document;
	}

	public function get_image()
	{
		return image($this->filename, $this->type, $this->created_at);
	}

	public function get_imagePath()
	{
		return imagePath($this->filename, $this->type, $this->created_at);
	}

	public function get_document()
	{
		return document($this->filename, $this->type, $this->created_at);
	}

	public function get_documentPath()
	{
		return documentPath($this->filename, $this->type, $this->created_at);
	}

	public function get_extension()
	{
		return File::extension($this->path);
	}

	public function isImage()
	{
		return File::exists($this->imagePath);
	}

	public function __get($name)
	{
		if (array_key_exists($name, Config::find('image.aliases', array())))
		{
			$dimensions = Config::find('image.aliases.'.$name);
			$original = imagePath($this->filename, $this->type, $this->created_at);

			$name .= '_' . $this->filename;
			$thumbnail = imagePath($name, $this->type, $this->created_at);
			$thumbUrl = image($name, $this->type, $this->created_at);

			if (!File::exists($thumbnail) and File::exists($original))
			{
				Image::make($original)->
					resize($dimensions[0], $dimensions[1], Arr::getItem($dimensions, 2, true), false)->
					save($thumbnail);
			}

			return $thumbUrl;
		}

		return parent::__get($name);
	}
}
