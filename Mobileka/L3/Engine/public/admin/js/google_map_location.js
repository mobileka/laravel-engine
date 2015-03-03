var googleMapInit = function(field) {
	var lat = parseFloat(app.map[field].latitude) ? parseFloat(app.map[field].latitude) : app.map.default.latitude,
		lon = parseFloat(app.map[field].longitude) ? parseFloat(app.map[field].longitude) : app.map.default.longitude,
		zoom = ($('#zoom').length && $('#zoom').val() ? $('#zoom').val() : app.map.default.zoom);

	if (lat && lon) {
		latlng = new google.maps.LatLng(lat, lon);
	}

	var map = new google.maps.Map(document.getElementById(field), {
		center: latlng,
		zoom: parseInt(zoom),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var marker = new google.maps.Marker({
		position: latlng,
		map: map,
		title: 'Drag the marker to specify coordinates',
		draggable: true
	});

	google.maps.event.addListener(marker, 'dragend', function(a) {
		$('#' + field + '_latitude').val(a.latLng.lat());
		$('#' + field + '_longitude').val(a.latLng.lng());
	});

	google.maps.event.addListener(map, 'zoom_changed', function() {
		$('#zoom').val(this.zoom);
	});
};