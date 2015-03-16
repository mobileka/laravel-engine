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
		maxZoom: 15,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var input = document.getElementById('pac-input');
	map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
	var searchBox = new google.maps.places.SearchBox(input);

	// Listen for the event fired when the user selects an item from the
	// pick list. Retrieve the matching places for that item.
	google.maps.event.addListener(searchBox, 'places_changed', function() {
		var places = searchBox.getPlaces();

		if (places.length == 0) {
			return;
		}

		var place = places[0];
		var bounds = new google.maps.LatLngBounds();
		app.map.marker = new google.maps.Marker({
			map: map,
			title: place.name,
			draggable: true,
			position: place.geometry.location
		});

		$('#' + field + '_latitude').val(place.geometry.location.k);
		$('#' + field + '_longitude').val(place.geometry.location.D);

		google.maps.event.addListener(app.map.marker, 'dragend', function(a) {
			$('#' + field + '_latitude').val(a.latLng.lat());
			$('#' + field + '_longitude').val(a.latLng.lng());
		});

		bounds.extend(place.geometry.location);

		map.fitBounds(bounds);
	});

	// Bias the SearchBox results towards places that are within the bounds of the
	// current map's viewport.
	google.maps.event.addListener(map, 'bounds_changed', function() {
		var bounds = map.getBounds();
		searchBox.setBounds(bounds);
	});

	app.map.marker = new google.maps.Marker({
		position: latlng,
		map: map,
		title: 'Drag the marker to specify coordinates',
		draggable: true
	});

	google.maps.event.addListener(app.map.marker, 'dragend', function(a) {
		$('#' + field + '_latitude').val(a.latLng.lat());
		$('#' + field + '_longitude').val(a.latLng.lng());
	});

	google.maps.event.addListener(map, 'zoom_changed', function() {
		$('#zoom').val(this.zoom);
	});

	$('.map-controls').closest('form').submit(function(e) {
		var focusedElement = $(':focus');
		if (focusedElement.attr('id') == 'pac-input') {
			return false;
		}
	});
};