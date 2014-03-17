(function($) {
	$.fn.yandexMapLocation = function() {
		return this.each(function() {
			var $elem = $(this), mark, map = new ymaps.Map($elem.get(0), {
				center: [43.249350, 76.910267],
				zoom: 12,
				type: 'yandex#publicMap',
				behaviors: ['default', 'scrollZoom']
			});
			var searchControl = new ymaps.control.SearchControl({
				noPlacemark: true
			});

			function createPlacemark(coords) {
				if (mark) {
					map.geoObjects.remove(mark);
				}
				mark = new ymaps.Placemark(coords, {}, { draggable: true });
				$elem.trigger('locationchanged', { latitude: coords[0], longitude: coords[1] });
				mark.events.add('geometrychange', function(e) {
					// For a placemark 2 bound point coordinates will be equal, i.e.
					// bounds[0] == bounds[1]
					var bounds = mark.geometry.getBounds();
					var coords = bounds[0];
					$elem.trigger('locationchanged', { latitude: coords[0], longitude: coords[1] });
				});
				map.geoObjects.add(mark);
			}

			var data = $elem.data();
			if (data.latitude && data.longitude) {
				createPlacemark([data.latitude, data.longitude]);
				map.setCenter([data.latitude, data.longitude], 15, { checkZoomRange: true });
			}

			map.controls
				.add('zoomControl')
				.add('mapTools')
				.add(searchControl);

			map.events.add('click', function(e) {
				createPlacemark(e.get('coordPosition'));
			});
		});
	};
}(jQuery));
