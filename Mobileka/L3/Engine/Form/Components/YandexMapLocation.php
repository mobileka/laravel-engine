<?php namespace Mobileka\L3\Engine\Form\Components;

/**
 * Choose location on a map using Yandex maps
 */
class YandexMapLocation extends BaseComponent {

	protected $template = 'engine::form.location_yandex';

	// Unique string identifying object whose latitude and longitude is
	// being specified. If you specify only one location in your form, you
	// don't need to do anything.
	protected $id = 'default';

	// This must be either 'latitude' or 'longitude'
	protected $type;

	// Keeps track of what has been rendered so far.
	protected static $rendered = array();

	public function render($lang = '')
	{
		if (!in_array($this->type, array('latitude', 'longitude')))
		{
			throw new \RuntimeException('YandexMapLocation::$type must be either latitude or longitude');
		}

		\Asset::container('engine_assets')->add(
			'yandex_maps',
			'https://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=' . \Config::get('application.language')
		)->add(
			'yandex_map_location',
			'bundles/engine/admin/js/yandex_map_location.js'
		);

		$result = parent::render($lang);
		static::$rendered[$this->mapId()] = true;

		return $result;
	}

	public function mapId()
	{
		return "yandex_map_location_$this->id";
	}

	public function isHidden()
	{
		return isset(static::$rendered[$this->mapId()]);
	}
}
