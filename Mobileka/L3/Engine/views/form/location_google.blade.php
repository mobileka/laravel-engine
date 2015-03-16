<?php
$id = $component->mapId();
$inputId = $id . '_' . $component->type;
$defaultValue = Input::old($inputOldName, $component->value($lang));
echo Form::hidden($name, $defaultValue, array('id' => $inputId));
?>

<input id="pac-input" class="map-controls" type="text" placeholder="Search Box">
<script>
app.map = app.map || {};
if (!app.map.default) {
	app.map.default = {
		latitude:parseFloat('{{ Config::get('application.default_latitude', '43.2499541711441') }}'),
		longitude: parseFloat('{{ Config::get('application.default_longitude', '76.9193172454834') }}'),
		zoom: parseInt('{{ Config::get('application.default_zoom', 10) }}')
	};
}
app.map['{{ $id }}'] = app.map['{{ $id }}'] || {};
app.map['{{ $id }}'].{{ $component->type }} = '{{ $component->value($lang) }}';

jQuery(function($) {
	var $mapContainer = $('#{{ $id }}'), field = '{{ $component->type }}',
		$input = $('#{{ $inputId }}'), initMap = true;

	if ($mapContainer.length === 0) {
		$mapContainer = $('<div></div>', {
			id: '{{ $id }}',
			style: 'width:100%; height:400px'
		}).addClass('google-maps-container')
		.insertAfter('#{{ $inputId }}');

		initMap = false;
	}

	$mapContainer.data(field, '{{ $defaultValue }}');
	$mapContainer.on('locationchanged', function(event, coordinates) {
		$input.val(coordinates[field]);
	});

	if (initMap) {
		googleMapInit('{{ $id }}');
	}
});
</script>
