jQuery(document).ready(function($) {
	/*
		Если селект в фильтрах грида, нужно выбрать подстроку между последними квадратными скобками.
		Если в форме, то просто имя селектбокса.
	*/
	app.getNameAttr = function(object) {
		var attrName = object.attr('name');

		return attrName.indexOf('filters[') !== -1
			? object.attr('name').substr(15, object.attr('name').length - 16)
			: attrName;
	}

	app.getNextObjectKey = function(current, array) {
		return app.getNextObjectElement(current, array, true);
	}

	app.getNextObjectElement = function(current, array, returnKey) {
		var return_value = false;

		for (var i in array) {
			if (return_value) {
				return returnKey ? i : array[i];
			}

			if (current == i) {
				return_value = true;
			}
		}

		return false;
	}

	app.updateNextSelect = function(list, nameInDb) {
		var disableNext = false;

		for (var i in list) {
			if (disableNext) {
				$(app.selectorPrefix + i + app.selectorPostfix).html('')
					.prop('disabled', true)
					.trigger("liszt:updated");
			}

			if (i == nameInDb) {
				disableNext = true;
			}
		}
	}

	app.fillNextSelect = function(nameInDb, val, list) {
		var result = false,
			model  = list[nameInDb];

		if (!model) {
			return false;
		}

		$.ajax({
			url: URL_KEEPER.admin_linked_list + '/' + model + '/' + val,
			async: false
		}).success(function(data) {

			if (data != '' && data.length !== 0) {

				var nextNameInDb = app.getNextObjectKey(nameInDb, list),
					target = $(app.selectorPrefix + nextNameInDb + app.selectorPostfix).attr('data-placeholder', 'Не выбрано')
						.html('')
						.append('<option value="">Не выбрано</option>');

				for (var i in data) {
					target.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
				}

				target.prop('disabled', false)
					.trigger("liszt:updated");

				app.updateNextSelect(list, nextNameInDb);

				result = true;
			}
			// Если пришёл пустой массив, попробовать запустить это для следующего селектбокса
			else if (data.length === 0) {

				var nextNameInDb = app.getNextObjectKey(nameInDb, list);

				$(app.selectorPrefix + nextNameInDb + app.selectorPostfix).val('')
					.prop('disabled', true)
					.trigger("liszt:updated");

				var afterNextNameInDb = app.getNextObjectKey(nextNameInDb, list),
					afterNextModel    = app.getNextObjectElement(nextNameInDb, list);

				if (!afterNextModel) {
					return false;
				}

				$.ajax({
					url: URL_KEEPER.admin_linked_list + '/' + afterNextModel + '/' + val, async: false}).success(function(data) {

					if (data && data.length) {
						var target = $(app.selectorPrefix + afterNextNameInDb + app.selectorPostfix).attr('data-placeholder', 'Не выбрано')
								.html('')
								.append('<option value="">Не выбрано</option>');

						for (var i in data) {
							target.append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
						}

						target.prop('disabled', false)
							.trigger("liszt:updated");

						app.updateNextSelect(list, afterNextNameInDb);

						result = true;
					}
				});
			}

		});

		return result;
	}

	app.updateAllSelects = function(currentModelCondition) {
		for (var nameInDb in currentModelCondition) {
			var currentSelectValue = currentModelCondition[nameInDb],
				self = $(app.selectorPrefix + nameInDb + app.selectorPostfix);

			self.children().each(function(i, el) {
				var currentOption = $(el);

				if (currentOption.val() == currentSelectValue) {
					var result = app.fillNextSelect(nameInDb, currentOption.val(), app.linked_items);

					currentOption.attr('selected', true);

					self.trigger("liszt:updated");
				}
			});
		}
	}

	app.updateAllSelects(app.current);

	for (var fieldInDb in app.linked_items) {
		var fieldName = app.linked_items[fieldInDb];


		$(app.selectorPrefix + fieldInDb + app.selectorPostfix).change(function() {

			var val      = $(this).val(),
				nameInDb = app.getNameAttr($(this));

			if (!nameInDb) {
				return;
			}

			if (val !== '') {
				app.fillNextSelect(nameInDb, val, app.linked_items);
			}
			else {
				app.updateNextSelect(app.linked_items, nameInDb);
			}
		});
	}
});