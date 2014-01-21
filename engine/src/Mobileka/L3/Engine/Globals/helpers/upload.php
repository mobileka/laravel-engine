<?php

function image($filename, $type, $created_at, $directory = 'images')
{
	return URL::base() . '/uploads/' . $directory . '/' . $type . '/' .\Date::make($created_at)->get('Y-m') . '/' . $filename;
}

function imagePath($filename, $type, $created_at, $directory = 'images')
{
	return path('uploads') . $directory . '/' . $type . '/' .\Date::make($created_at)->get('Y-m') . '/' . $filename;
}

function document($filename, $type, $created_at, $directory = 'docs')
{
	return URL::base() . '/uploads/' . $directory . '/' . $type . '/' .\Date::make($created_at)->get('Y-m') . '/' . $filename;
}

function documentPath($filename, $type, $created_at, $directory = 'docs')
{
	return path('uploads') . $directory . '/' . $type . '/' .\Date::make($created_at)->get('Y-m') . '/' . $filename;
}

function dummyThumbnail($alias = null)
{
	return \URL::base() . '/img/elements/dummy_thumbnail' . ($alias ? '_' . $alias : '') . '.jpg';
}