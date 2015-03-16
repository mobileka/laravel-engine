<?php namespace Mobileka\L3\Engine\Form\Components;

/**
 * Choose location on a map using Google maps
 */
class GoogleMapLocation extends YandexMapLocation {

	protected $template = 'engine::form.location_google';

	public function render($lang = '')
	{
		if (!in_array($this->type, array('latitude', 'longitude')))
		{
			throw new \RuntimeException('GoogleMapLocation::$type must be either latitude or longitude');
		}

		\Asset::container('engine_assets')
			->add('google-map-styles', 'bundles/engine/admin/css/google-map-location.css')
			->add('google-map', 'http://maps.google.com/maps/api/js?sensor=false&libraries=places')
			->add('google_map_location', 'bundles/engine/admin/js/google_map_location.js');

		$result = parent::render($lang);
		static::$rendered[$this->mapId()] = true;

		return $result;
	}

	public function mapId()
	{
		return "google_map_location_$this->id";
	}

	public function isHidden()
	{
		return isset(static::$rendered[$this->mapId()]);
	}
}
