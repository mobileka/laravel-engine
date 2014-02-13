/**
Autocomplete editable input.
Internally value stored as {city: "Moscow", street: "Lenina", building: "15"}

@class autocomplete
@extends abstractinput
@final
@example
<a href="#" id="autocomplete" data-type="autocomplete" data-pk="1">awesome</a>
<script>
$(function(){
    $('#autocomplete').editable({
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
var clients = [];

(function ($) {
    var Autocomplete = function (options) {
        this.init('autocomplete', options, Autocomplete.defaults);
        clients = []; // It is can be optimized
        for (var key in $(options.scope).data('source')) {
			clients.push(key);
		}
    };

    //inherit from Abstract input
    $.fn.editableutils.inherit(Autocomplete, $.fn.editabletypes.abstractinput);

    $.extend(Autocomplete.prototype, {
        /**
        Renders input from tpl

        @method render() 
        **/        
        render: function() {
           this.$input = this.$tpl.find('input');
        	var input = this.$input,
				fx = this;
        this.$input.autocomplete({
				source: clients,
				select: function( event, ui ) {
					fx.value2html(ui.item.value, input);
					$(input).val(ui.item.value);
					$(input).closest('form').submit();
				}
			});

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
            var html = $('<div>').text(value).html();
            $(element).html(html); 
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
           if(!value) {
             return;
           }
           this.$input.filter('[name="client"]').val(value);
       },       
       
       /**
        Returns value of input.
        
        @method input2value() 
       **/          
       input2value: function() { 
           return this.$input.filter('[name="client"]').val();
       },        
       
        /**
        Activates input: sets focus on the first field.
        
        @method activate() 
       **/        
       activate: function() {
            this.$input.filter('[name="client"]').focus();
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

    Autocomplete.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
        tpl: '<div class="editable-autocomplete"><label><span>Клиент: </span><input type="text" name="client"></label></div>',
        inputclass: ''
    });

    $.fn.editabletypes.autocomplete = Autocomplete;

}(window.jQuery));