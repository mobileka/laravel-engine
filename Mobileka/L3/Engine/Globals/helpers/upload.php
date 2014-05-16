<?php

function image($filename, $type, $created_at, $directory = 'images')
{
	return \URL::base() . '/uploads/' . $directory . '/' . $type . '/' .\Date::make($created_at)->get('Y-m') . '/' . $filename;
}

function imagePath($filename, $type, $created_at, $directory = 'images')
{
	return path('uploads') . $directory . '/' . $type . '/' .\Date::make($created_at)->get('Y-m') . '/' . $filename;
}

function document($filename, $type, $created_at, $directory = 'docs')
{
	return \URL::base() . '/uploads/' . $directory . '/' . $type . '/' .\Date::make($created_at)->get('Y-m') . '/' . $filename;
}

function documentPath($filename, $type, $created_at, $directory = 'docs')
{
	return path('uploads') . $directory . '/' . $type . '/' .\Date::make($created_at)->get('Y-m') . '/' . $filename;
}

function dummyThumbnail($alias = null)
{
	$alias = $alias ? : 'original';
	$dimensions = Mobileka\L3\Engine\Laravel\Config::find('image.aliases.'.$alias, array());

	if ($dimensions)
	{
		\Image::make(path('public').'/bundles/engine/img/elements/dummy_thumbnail_original.png')->
			resize($dimensions[0], $dimensions[1], true, false)->
			save(path('public').'/uploads/dummy_thumbnail_'.$alias.'.png');

		return \URL::base() . '/uploads/dummy_thumbnail_' . $alias . '.png';
	}

	return \URL::base() . '/bundles/engine/img/elements/dummy_thumbnail_original.png';
}

function imagePlaceholder()
{
	return \URL::base() . '/bundles/engine/img/elements/placeholder.gif';
}