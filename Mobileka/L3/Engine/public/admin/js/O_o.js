(function(w, $){
	w.O_o = {};

	O_o.ajax = function(options) {
		var self = this;
		this.options = options;

		// set type
		if (self.options.data != undefined) {
			if (self.options.type == undefined) {
				if (self.options.data._method != undefined) {
					self.options.type = 'POST';
				} else {
					self.options.type = 'GET';
				}
			} 
		}

		// set DataType
		if (self.options.dataType == undefined) {
			self.options.dataType = 'JSON';
		}

		if (self.options.error == undefined) {
			var self_url = self.options.url;
			self.options.error = function(obj) {
				O_o.hello('AJAX error: ' + self_url, 'red', 'fire', self.options.url, obj.responseText);
				console.log(self.options.url);
				console.error(obj.responseText);
			}
		}

		// set Url
		self.options.url = Url.ajax(self.options.url);
		$.ajax(self.options);
	};

	O_o.hash = function () {
		var attrs = [],
			params = [],
			attrMap = [],
			url = location.hash;
		if (url != '') {
			attrs = (url.substr(1)).split('&');	 // разделяем переменные
			for (var i = 0; i < attrs.length; i++) {
				params = attrs[i].split('=');			 // массив param будет содержать
				attrMap[params[0]] = params[1];			 // пары ключ(имя переменной)->значение
			}
		}
		return attrMap;
	};

	O_o.scrollTop = function (set_position) {
		if(set_position == undefined)
			set_position = 0;
		$("html:not(:animated),body:not(:animated)").animate({ scrollTop: set_position}, 1000 );
	};

	O_o.scrollBottom = function () {
		var document_height = $(document).height();
		O_o.scrollTop(document_height);
	};

	O_o.unserialize = function(form, data) {
		var form = $(form);
		for (var i in data) {
			$(form).find('[name=' + i + ']').val(data[i]);
		}
	};

	O_o.parseJson =	function (data) {
		if (typeof data === 'object') {
			return data;	
		} 
		return (new Function("return " + data))();	
	};

	O_o.isset = function (object, search_value) {
		return (object.indexOf(search_value) > -1);
	};

	O_o.alert = function (message_text, result){
		if(result == undefined)
			result = 'success';
		$('body').prepend(
			'<div class="O_o-disable_window"><div class="alert">' + 
				'<button class="close close_alert" type="button">×</button>' +
				message_text + 
				'<div class="buttons">' +
					'<a class="btn btn-'+result+' close_alert">Закрыть</a>' +
				'</div>' +
			'</div></div>');
		/*
			need window effect!
		$('.O_o-disable_window').click(function(){
			//$('.alert').hide();
			$('.alert').animate({effect: 'pulsate'});
			console.info('close');
		});
		*/

		$('.close_alert').click(function(){
			$('.O_o-disable_window').remove();
		});
	};

	O_o.confirm = function(message_text, positive, negative) {
		$('body').prepend(
			'<div class="O_o-disable_window"><div class="alert">' + 
				'<button class="close close_alert" type="button">×</button>' +
				message_text + 
				'<div class="buttons">' +
					'<a id="O_O-confirm-ok" class="btn btn-success close_alert">Да</a> ' +
					'<a id="O_O-confirm-not-ok" class="btn btn-inverse close_alert">Нет</a>' +
				'</div>' +
			'</div></div>');
		
		if (positive != undefined) {
			$('#O_O-confirm-ok').click(function(){
				positive();
			});
		}

		if (negative != undefined) {
			$('#O_O-confirm-not-ok').click(function(){		
				negative();
			});
		}

		$('.close_alert').click(function(){
			$('.O_o-disable_window').remove();
		});
	};

	O_o.prompt = function(message_text, form_action, prompt_area) {
		$('body').prepend(
			'<div class="O_o-disable_window"><div class="alert"><form action="'+form_action+'" method="post">' + 
				'<button class="close close_alert" type="button">×</button>' +
				message_text + 
				'<textarea class="prompt_area" name="' + prompt_area + '"></textarea>' +
				'<div class="buttons">' +
					'<input type="submit" value="Отправить" class="btn btn-success close_alert"> ' +
					'<input type="reset" value="Отмена" class="btn btn-inverse close_alert"> ' +
				'</div>' +
			'</form></div></div>');
		
		$('.close_alert').click(function(){
			$('.O_o-disable_window').remove();
		});
	};

	O_o.hello = function(text, color, icon, link, response){

		var timeout = 3000;

		if(icon !== undefined)
		{
			icon = '<i class="hello-icon-'+ icon +'"></i>';
		}
		else
		{
			icon = '';
		}

		if(color !== undefined)
		{
			color = ' hello-color-' + color;
		}
		else
		{
			color = '';
		}

		if(link !== undefined)
		{
			link = '<div class="response_link"><a title="Открыть запрос в новом окне" href="' + link + '" target="blank">' + link + '</a></div>';
		}
		else
		{
			link = '';
		}

		if(response !== undefined)
		{
			response = '<div class="response">' + response + '</div>';
		}
		else
		{
			response = '';
		}

		$('body').prepend('<div class="O_o-hello' + color + '" style="right: -500px">' + icon +  text + link + response + '</div>');
		var self = $('body').children('.O_o-hello').first();
		self.animate({right: 0}, 'slow');

		self.click(function(){
			self.fadeOut('slow', function(){ $(this).remove() } );
		});

		
		var timer = setTimeout(function () {
			self.fadeOut('slow', function(){ $(this).remove() } );
		}, timeout);

		self.mouseover(function(){
			clearTimeout(timer);
		});


	};

	O_o.key_code = function(e){
		return e.keyCode?e.keyCode:e.which;
	};

	O_o.caps_lock = function(){
		return {
			enabled : null,
			getChar : function(event){
				if (event.which == null) {
					if (event.keyCode < 32) return null;
					return String.fromCharCode(event.keyCode);
				}

				if (event.which!=0 && event.charCode!=0) {
					if (event.which < 32) return null;
					return String.fromCharCode(event.which);
				}

				return null;
			},
			toggleLabel : function(elem){
				label = $(elem).parent().children('.check_caps');
				if(this.enabled)
					label.fadeIn('fast');
				else
					label.fadeOut('fast');
			}
		};
	};

})(window, jQuery);