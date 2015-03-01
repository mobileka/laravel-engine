var googleMapInit = function(field) {
	if (field === 'default') {
		continue;
	}

	var lat = parseFloat(app.map[field].latitude) ? parseFloat(app.map[field].latitude) : app.map.default.latitude,
		lon = parseFloat(app.map[field].longitude) ? parseFloat(app.map[field].longitude) : app.map.default.longitude;

	if (lat && lon) {
		latlng = new google.maps.LatLng(lat, lon);
	}

	var map = new google.maps.Map(document.getElementById('form_map_' + field), {
		center: latlng,
		zoom: 14,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var marker = new google.maps.Marker({
		position: latlng,
		map: map,
		title: 'Drag the marker to specify coordinates',
		draggable: true
	});

	google.maps.event.addListener(marker, 'dragend', function(a) {
		$('#lat_' + field).val(a.latLng.lat());
		$('#lon_' + field).val(a.latLng.lng());
	});
};