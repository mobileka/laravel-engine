$(document).ready(function(){
	// Validate
	function validate_global_allow(key){
		if(key==8 || key==9 || (key>=35 && key<=40) || key==46)
		{
			return true;
		}
	};

	function validate_numeric_keys(key){
		if(key>=48 && key<=57 || key>=96 && key<=105)
		{
			return true;
		}
	};

	function validate_comma_dot(key){
		if (key == 188 || key == 110 || key == 190 || key == 191)
		{
			return true;
		}
	}

	function validate_enter(key){
		if (key == 13)
		{
			return true;
		}
	}

	function validate_space(key){
		if (key == 32)
		{
			return true;
		}
	}

	// compatible with jquery.validator
	$('body').on('keydown', 'input[data-rule-number=true]', function(e){
		var key = O_o.key_code(e),
			allow = false;

		if(validate_comma_dot(key))
		{
			var val = $(this).val(),
				pos_start = e.target.selectionStart,
				pos_end = e.target.selectionEnd,
				len = val.length,
				val_left = val.substr(0,pos_start),
				val_right = val.substr(pos_end,len);
			$(this).val(val_left + '.' + val_right);
			this.selectionStart = this.selectionEnd = pos_start +1;
		}
		
		if(
			validate_numeric_keys(key) || 
			key == 46 || 
			validate_global_allow(key) || 
			validate_enter(key)
		)
			allow = true;

		if(!allow)
			e.preventDefault();
	});


	/* unuse
	$('.allow_int').keypress(function(e){
		if(e.keyCode<48 || e.keyCode>57)
			e.preventDefault();
	});

	$('.allow_float').keypress(function(e){
		var key = O_o.key_code(e),
			allow = false;

		if(key == 44)
		{
			var val = $(this).val(),
				pos_start = e.target.selectionStart,
				pos_end = e.target.selectionEnd,
				len = val.length,
				val_left = val.substr(0,pos_start),
				val_right = val.substr(pos_end,len);
			$(this).val(val_left + '.' + val_right);
			this.selectionStart = this.selectionEnd = pos_start +1;
		}
		
		if(key>=48 && key<=57 || key == 46 || validate_global_allow(key))
			allow = true;

		if(!allow)
			e.preventDefault();
	});
	*/
});