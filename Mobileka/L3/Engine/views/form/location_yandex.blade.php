<?php
$id = $component->mapId();
$inputId = $id . '_' . $component->type;
$defaultValue = Input::old($inputOldName, $component->value($lang));
echo Form::hidden($name, $defaultValue, array('id' => $inputId));
?>

<script>
jQuery(function($) {
	var $mapContainer = $('#{{ $id }}'), field = '{{ $component->type }}',
		$input = $('#{{ $inputId }}'), initMap = true;
	if ($mapContainer.length === 0) {
		$mapContainer = $('<div></div>', {
			id: '{{ $id }}',
			style: 'width:100%; height:400px'
		}).insertAfter('#{{ $inputId }}');
		initMap = false;
	}
	$mapContainer.data(field, '{{ $defaultValue }}');
	$mapContainer.on('locationchanged', function(event, coordinates) {
		$input.val(coordinates[field]);
	});

	if (initMap) {
		ymaps.ready(function() {
			$mapContainer.yandexMapLocation();
		});
	}
});
</script>
