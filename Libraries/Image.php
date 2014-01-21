<?php

class Image extends \Intervention\Image\Image {

	public function thumbnail($width = 0, $height = 0, $aspect = true, $upzising = false)
	{
		return $this->resize(
			\Misc::truthyValue($width, \Config::get('application.thumbnailWidth')),
			\Misc::truthyValue($height, null),
			$aspect,
			$upzising
		);
	}

}