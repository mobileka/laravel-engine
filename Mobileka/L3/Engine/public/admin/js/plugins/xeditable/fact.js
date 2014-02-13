/**
Fact editable input.
Internally value stored as {city: "Moscow", street: "Lenina", building: "15"}

@class fact
@extends abstractinput
@final
@example
<a href="#" id="fact" data-type="fact" data-pk="1">awesome</a>
<script>
$(function(){
		$('#fact').editable({
				url: '/post',
				title: 'Enter city, street and building #',
				value: {
						city: "Moscow", 
						street: "Lenina", 
						building: "15"
				}
		});
});
</script>
**/
(function ($) {
		var Fact = function (options) {
			this.init('fact', options, Fact.defaults);
		};

		//inherit from Abstract input
		$.fn.editableutils.inherit(Fact, $.fn.editabletypes.abstractinput);

		$.extend(Fact.prototype, {
				/**
				Renders input from tpl

				@method render() 
				**/        
				render: function() {
					 this.$input = this.$tpl.find('input');
				},
				
				/**
				Default method to show value in element. Can be overwritten by display option.
				
				@method value2html(value, element) 
				**/
				value2html: function(value, element) {
						if(!value) {
								$(element).empty();
								return; 
						}
						//var html = 
						//$('<div>').text(value.date_fact).html() + ', ' + $('<div>').text(value.sum_fact).html() + ' st., bld. ';
						//$('<div>').text(value.date_fact).html() + ', ' + $('<div>').text(value.sum_fact).html() + ' st., bld. ';
						$(element).closest('tr').find('[data-name=date_fact]').text(value.date_fact); 
						$(element).closest('tr').find('[data-name=sum_fact]').text(value.sum_fact); 
				},
				
				/**
				Gets value from element's html
				
				@method html2value(html) 
				**/        
				html2value: function(html) {   
					/*
						you may write parsing method to get value by element's html
						e.g. "Moscow, st. Lenina, bld. 15" => {city: "Moscow", street: "Lenina", building: "15"}
						but for complex structures it's not recommended.
						Better set value directly via javascript, e.g. 
						editable({
								value: {
										city: "Moscow", 
										street: "Lenina", 
										building: "15"
								}
						});
					*/ 
					return null;  
				},
			
			 /**
				Converts value to string. 
				It is used in internal comparing (not for sending to server).
				
				@method value2str(value)  
			 **/
			 value2str: function(value) {
					 var str = '';
					 if(value) {
							 for(var k in value) {
									 str = str + k + ':' + value[k] + ';';  
							 }
					 }
					 return str;
			 }, 
			 
			 /*
				Converts string to value. Used for reading value from 'data-value' attribute.
				
				@method str2value(str)  
			 */
			 str2value: function(str) {

			 	if(typeof str == 'object')
			 	{
			 		return str;
			 	}
			 	else
			 	{
			 		var val = str.split(',');
			 		return {
			 			date_fact: parseInt(val[0])?moment(val[0]).format('DD.MM.YYYY'):'n/a', //val[0].substr(0, 10),
			 			sum_fact: val[1]
			 		}			 		
			 	}

					 /*
					 this is mainly for parsing value defined in data-value attribute. 
					 If you will always set value by javascript, no need to overwrite it
					 */
					 return str;
			 },                
			 
			 /**
				Sets value of input.
				
				@method value2input(value) 
				@param {mixed} value
			 **/         
			 value2input: function(value) {
			 	return false;
					 if(!value) {
						 return;
					 }
					 this.$input.filter('[name="date_fact"]').val(value.date_fact);
					 this.$input.filter('[name="sum_fact"]').val(value.sum_fact);
			 },       
			 
			 /**
				Returns value of input.
				
				@method input2value() 
			 **/          
			 input2value: function() { 
					 return {
							date_fact: this.$input.filter('[name="date_fact"]').val(), 
							sum_fact: this.$input.filter('[name="sum_fact"]').val()
					 };
			 },
			 
				/**
				Activates input: sets focus on the first field.
				
				@method activate() 
			 **/        
			activate: function() {
				var picker = this.$input.filter('[name="date_fact"]');
				picker.datepicker({
					format: 'dd.mm.yyyy',
					language: 'ru',
					autoclose: true,
					weekStart: 1,
				});
				setTimeout(function(){ picker.focus() }, 100);
			},  
			 
			 /**
				Attaches handler to submit form in case of 'showbuttons=false' mode
				
				@method autosubmit() 
			 **/       
			 autosubmit: function() {
					 this.$input.keydown(function (e) {
								if (e.which === 13) {
										$(this).closest('form').submit();
								}
					 });
			 }       
		});

		Fact.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
				tpl: '<div class="editable-fact"><label><span>Дата факт.: </span><input type="text" name="date_fact" class="datepicker input-small"></label></div>'+
					 '<div class="editable-fact"><label><span>Сумма факт.: </span><input type="text" name="sum_fact" data-rule-number="true" class="input-small"></label></div>', 
						 
				inputclass: ''
		});

		$.fn.editabletypes.fact = Fact;

}(window.jQuery));