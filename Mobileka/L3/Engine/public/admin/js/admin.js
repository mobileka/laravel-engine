	var keyboard = {
		exceptions : [
			16, // shift
			17, // ctrl
			18, // alt
			20, // caps lock
		]
	};

$(document).ready(function() {
	$('.delete-toggle').each(function(index,elem) {
		$(elem).click(function(e) {
			e.preventDefault();
			var confirm_message = $(elem).data('confirm_message');
			$('#crud_delete_form').attr('action', $(elem).data('url'));

			$('p.modal-confirm-message').html(confirm_message);

			$('#delete_modal').modal('show');
		});
	});

	$('#module_name').change(function() {
		$.get(BASE+'/admin/returnviews/'+this.value, function(data) {
			$('#tmp_name').html(data);
		});
	});

	$('.ibecTranslit_ru').keypress(function(event)
	{
		var text = ibecTranslit(this);
		$('.ibecTranslitDestination').val(text);

		if(keyboard.exceptions.indexOf(event.which) == -1)
		{
			var text = ibecTranslit(this);
			var url;

			if(text != '' || text != undefined) {
			url = 'articles/category/'+text;
			} else {
				url='';
			}

			$('.ibecTranslitDestination').val(text);
			$('.categoryurl').val(url);
		}
	});

	$('.ibecTranslit_ru').blur(function()
	{
		var text = ibecTranslit(this);
		$('.ibecTranslitDestination').val(text);
	});




	$('.ibecCategoryTranslit_ru').keyup(function(event)
	{
		if(keyboard.exceptions.indexOf(event.which) == -1)
		{
			var text = ibecTranslit(this);
			var url;

			if(text != '' || text != undefined) {
			url = 'articles/category/'+text;
			} else {
				url='';
			}

			$('.ibecTranslitDestination').val(text);
			$('.categoryurl').val(url);
		}
	});

//    $('.ibecTranslitDestination').keyup(function(event)
//    {
//        if(keyboard.exceptions.indexOf(event.which) == -1)
//        {
//            $('.categoryurl').val('articles/category/'+$('.ibecTranslitDestination').val());
//        }
//    });

	$('.ibecCategoryTranslit_ru').blur(function()
	{
		var text = ibecTranslit(this);
		var url;
		if(text) {
		   url = 'articles/category/'+text;
		} else {
			url='';
		}

		$('.ibecTranslitDestination').val(text);
		$('.categoryurl').val(url);
	});

	var ibecTranslit = function(self) {
		var arr={
		  "А" : "a", "Б" : "b", "В" : "v", "Г" : "g",
		  "Д" : "d", "Е" : "e", "Ё" : "e", "Ж" : "j", "З" : "z", "И" : "i",
		  "Й" : "i", "К" : "k", "Л" : "l", "М" : "m", "Н" : "n",
		  "О" : "o", "П" : "p", "Р" : "r", "С" : "s", "Т" : "t",
		  "У" : "u", "Ф" : "f", "Х" : "h", "Ц" : "ts", "Ч" : "ch",
		  "Ш" : "sh", "Щ" : "sch", "Ъ" : "", "Ы" : "y", "Ь" : "",
		  "Э" : "e", "Ю" : "yu", "Я" : "ya", "а" : "a", "б" : "b",
		  "в" : "v", "г" : "g", "д" : "d", "е" : "e", "ё" : "e", "ж" : "j",
		  "з" : "z", "и" : "i", "й" : "y", "к" : "k", "л" : "l",
		  "м" : "m", "н" : "n", "о" : "o", "п" : "p", "р" : "r",
		  "с" : "s", "т" : "t", "у" : "u", "ф" : "f", "х" : "h",
		  "ц" : "ts", "ч" : "ch", "ш" : "sh", "щ" : "sch", "ъ" : "",
		  "ы" : "y", "ь" : "", "э" : "e", "ю" : "yu", "я" : "ya",
		  " " :  "_", "." :  "", "/" :  "_", '!' : '', '?' : ''
		};


		var text = $(self).val();

		var replacer = function(a) {
			var str;
			if(arr.hasOwnProperty(a) == true) {
				console.log(11);
			   str = arr[a];
			} else {
				console.log(22);
			   str = a;
			}
			return str;
		}
		if(text.match(/[А-яA-z0-9ёЁ.\s]/g)) {
			text = text.replace(/[А-яёЁ.\s]/g,replacer);
			return text;
		}
	}

	$(".categoryTree").each(function() {
		var $el = $(this),
		opt = {
			debugLevel: 0,
			onDeactivate: function() {
				$('.jstree-buttons #delete').addClass('disabled');
			},
			onActivate: function(node) {
				$('#node_lang_form_title').html('Изменить категорию');
				$('.jstree-buttons #delete').removeClass('disabled');
				$('#node_id').val(node.data.id);
				$('.node-lang-form').fadeIn(200);
				$('#editCat').removeClass('disabled').attr('href', '/admin/categories/'+node.data.id+'/edit');

				$('#postvalue').attr('value',node.data.id);


				for (var lang in node.data.langs) {
					$('input[name="name[' + lang + ']"]').val(node.data.langs[lang]);
				}
			}
		};

		opt.dnd = {
		  onDragStart: function(node) {
			return true;
		  },
		  onDragStop: function(node) {

		  },
		  preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
		  onDragEnter: function(node, sourceNode) {
			return true;
		  },
		  onDragOver: function(node, sourceNode, hitMode) {
			// Prevent dropping a parent below it's own child
			if(node.isDescendantOf(sourceNode)){
			  return false;
			}
			// Prohibit creating childs in non-folders (only sorting allowed)
			if( !node.data.isFolder && hitMode === "over" ){
			  return "after";
			}
		  },
		  onDrop: function(node, sourceNode, hitMode, ui, draggable) {

			if (hitMode === 'over') {
				node.data.isFolder = true;
				sourceNode.expand(true);
			}

			sourceNode.move(node, hitMode);
			$.post( CONTROLLER_URL + 'move', {
				node_id     : sourceNode.data.id,
				target_id   : node.data.id,
				mode        : hitMode
			}, function(data) {
				if (data.result == 'error') {
					console.log(data.result);
				}
			});

		  },
		  onDragLeave: function(node, sourceNode) {

		  }
		};

		$el.dynatree(opt);

	});

	if($(".categoryTree").length != 0) {
		$(".categoryTree").resizable({
			minWidth: 240,
			maxHeight: $(".categoryTree").height()
		});
	}

	var save_category = function() {
		var id   = $('#node_id').val();
		var name = {};
		var lang_names = $('.node-name');

		lang_names.each(function() {
			var lang = $(this).attr('data-lang');
			var value = $(this).val();

			name[lang] = $(this).val();
		});

		$.post( CONTROLLER_URL + 'save', {
			id   : id,
			name : name
		}, function(data) {
			$('#node_id').val(data.id);

			if (id) {
				var node = $(".categoryTree").dynatree("getActiveNode");
				node.data.title = data.name.ru;
				node.data.langs = data.name;
				node.render();
			}
			else {
				var rootNode = $(".categoryTree").dynatree('getRoot');
				rootNode.addChild({
					id    : data.id,
					title : data.name.ru,
					langs : data.name
				});
			}

			$('.no-categories').hide();

		});

	};

	$('.jstree-buttons #add_default').click(function() {
		$('#node_lang_form_title').html('Новая категория');
		$('.node-lang-form').fadeIn(200);

		$('.node-lang-form [type="text"], #node_id').val('');
	});

	$('.node-lang-form').submit(function(e) {
		e.preventDefault();

		save_category();
	});


	$('.jstree-buttons #delete').click(function() {
		if($(this).hasClass('disabled')) {
			return
		} else {
			$('#delete_user').modal('show');
		}
	});

	$('#delete_category').click(function() {
		var node = $('.categoryTree').dynatree('getActiveNode');
		var id   = node.data.id;

		if (!id) {
			alert("Не выбрана категория");
			return;
		}

		$.post( CONTROLLER_URL + 'delete', {
			id : id
		}, function(data) {
			node.remove();
			$('.node-lang-form [type="text"]').val('');
		});
	});

	$('.price_with_discount_toggle').click(function() {
		var show = $(this).is(':checked');
		if (show) {
			$('#price_with_discount').removeClass('hidden');
		}
		else {
			$('#price_with_discount').addClass('hidden');
		}

	});

	if ($('.icheck-me-grid').length) {
		$('.icheck-me-grid').change(function(e) {
			var value = ($(this).is(':checked') ? $(this).data('checkedvalue') : $(this).data('uncheckedvalue')),
				url = $(this).data('url')
				data = {};

			data[$(this).attr('name')] = value;

			$.ajax({
				url: url,
				method: 'PUT',
				data: data,
				success: function(response) {
					// console.log('success');
				}
			});
		});
	}

	if ($('.nav-tabs').length) {
		$('.nav-tabs a').click(function(e) {
			e.preventDefault();
			$(this).tab('show');
		});
	}

	$('body').on('click', '.ms-elem-selectable, .ms-elem-selection', function(e) {
		e.preventDefault();

		var type       = $(this).closest('.tab-pane').attr('id'),
			object_id  = $(this).attr('id').split('-')[0],
			model_id   = $('#model_id').val(),
			selectable = $(this).hasClass('ms-elem-selectable'),
			url        = selectable ? $('#create_url').val() : $('#remove_url').val(),
			method     = selectable ? 'POST' : 'DELETE';

		$.ajax({
			url: url,
			method: method,
			data: {
				object_id: object_id,
				type: type,
				model_id: model_id
			},
			success: function(response) {
				console.log(response);
			}
		});

	});

	if ($('.th-sortable').length) {
		var form = $('.form-grid-filters');

		$('.th-sortable').click(function(e) {
			e.preventDefault();
			var order = $(this).data('order').replace('__', '.'),
				order_field = form.find('[name="order[]"][value^="' + order + '"]'),
				new_value = order + ':desc';

			form.find('[name="order[]"]').not(order_field).remove();

			if (order_field.length) {
				if (order_field.val().indexOf(':desc') !== -1) {
					new_value = order + ':asc';
				}

				order_field.val(new_value);
			}
			else {
				form.append('<input type="hidden" name="order[]" value="' + new_value + '">')
			}

			form.submit();
		});

		$('.th-sortable').each(function(index, element) {
			var order = $(element).data('order').replace('__', '.'),
				order_field = form.find('[name="order[]"][value^="' + order + '"]'),
				icon = 'icon-sort';

			if (order_field.length) {
				if (order_field.val().indexOf(':desc') !== -1) {
					icon = 'icon-sort-down';
				}
				else {
					icon = 'icon-sort-up';
				}
			}

			$(element).append('<i class="' + icon + '"></i>');
		});
	}

	if ($('.colorbox').length) {
		$('.colorbox').each(function(index, element) {
			var width  = $(element).data('width') ? $(element).data('width') : "80%",
				height = $(element).data('height') ? $(element).data('height') : "80%";

			$(element).colorbox({
				iframe: true,
				width:  width,
				height: height
			});
		});
	}

	if ($('.iframe-close').length) {
		$('.iframe-close').click(function() {
			$(this).parent().html('<div class="label label-success">Изменения успешно сохранены</div>');

			setTimeout(function() {
				parent.$.fn.colorbox.close();
			}, 1000);
		});
	}

});
