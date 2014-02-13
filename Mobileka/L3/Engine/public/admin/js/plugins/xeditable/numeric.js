(function ($) {
		var Numeric = function (options) {
				this.init('numeric', options, 
				{
					tpl: '<input type="text" data-rule-number="true" name="'+$(options.scope).data('name')+'" value="'+$(options.scope).text().trim()+'">',
					inputclass: ''
				});

		};

		$.fn.editableutils.inherit(Numeric, $.fn.editabletypes.abstractinput);

		$.extend(Numeric.prototype, {

				render: function() {
					 this.$input = this.$tpl;
				},

				value2html: function(value, element) {
						if(!value) {
								$(element).empty();
								return; 
						}
						$(element).html(value); 
				},

				html2value: function(html) {   
					return null;  
				},
			
			 value2str: function(value) {
					 var str = '';
					 if(value) {
							 for(var k in value) {
									 str = str + k + ':' + value[k] + ';';  
							 }
					 }
					 return str;
			 }, 
			 
			 str2value: function(str) {
					 return str;
			 },

			 value2input: function(value) {
			 	console.info('value2input');
					 if(!value) {
						 return;
					 }
					 this.$input.val(value);
			 },

			 input2value: function() {
					return this.$input.val();
			 },

			 activate: function() {
						this.$input.focus();
			 },

			 autosubmit: function() {
					 this.$input.keydown(function (e) {
								if (e.which === 13) {
										$(this).closest('form').submit();
								}
					 });
			 }
		});

		Numeric.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {});

		$.fn.editabletypes.numeric = Numeric;

}(window.jQuery));