<?php namespace Mobileka\L3\Engine\Laravel;

use Mobileka\L3\Engine\Laravel\Helpers\Misc;
use Mobileka\L3\Engine\Laravel\Config;

class Image extends \Intervention\Image\Image {

	public function thumbnail($width = 0, $height = 0, $aspect = true, $upzising = false)
	{
		return $this->resize(
			Misc::truthyValue($width, Config::get('application.thumbnailWidth')),
			Misc::truthyValue($height, null),
			$aspect,
			$upzising
		);
	}

}