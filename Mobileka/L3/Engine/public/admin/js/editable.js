	$(document).ready(function(){

		$('.table [data-type=date], .table [data-name=date_fact]').each(function(){
			var text = $(this).text();
			if(parseInt(text))
				text = moment(text).format('DD.MM.YYYY');
			else
				text = 'n/a';
			$(this).text(text);
		});


		$('.change-priority ul a').click(function(){
			$(this).closest('ul').find('i.icon-ok').removeClass('icon-ok');
			$(this).children('i').addClass('icon-ok');

			var line = $(this).closest('tr');
				form = line.find('form.form-horizontal'),
				name = 'priority',
				value = $(this).attr('rel');
				form.find('[name='+name+']').val(value);
				
				if(value == 3)
				{
					form.find('[name=finished]').val(1);
					//line.find('.editable').removeClass('editable').removeClass('editable-click').unbind('click').addClass('uneditable');
				}

			$(form).ajaxSubmit({
				success: function(){
					//line.attr('class', 'priority priority_'+value);
					//line.children('td.priority').attr('class', 'priority priority_'+value);
					location.reload();
				}
			});
		});


		$('.editable').editable({
			emptytext: 'не указано',
			datepicker: {
				language: 'ru'
			},
			success: function(data, value){

				var form_name = '';
				if(
					$(this).closest('tr').find('.parent_form').length && 
					$(this).data('name') != 'sum_fact' &&
					$(this).data('name') != 'date_fact'
				)
				{
					form_name = 'parent';
				}
				else
				{
					form_name = 'child';
				}


				var form = $(this).closest('tr').find('.'+form_name+'_form form.form-horizontal'),
					name = $(this).data('name');

				if($(this).data('type') == 'textarea')
				{
					value = value.replace("\n","<br>");
				}


				//if(value.sum_fact !== undefined)
				if($(this).data('type') == 'fact')
				{

					var date_name = 'date_fact',
						date = value.date_fact.split('.'),
						date_value = date[2]+'-'+date[1]+'-'+date[0];
					form.find('[name='+date_name+']').val(date_value);	
					form.append('<input type="hidden" name="payment" value="1">');

					name = 'sum_fact';
					value = value.sum_fact;
				}

				//else if(typeof value == 'object')
				if($(this).data('type') == 'date')
				{
					value = value.getFullYear()+'-'+(value.getMonth()+1)+'-'+value.getDate();
				}
				
					form.find('[name='+name+']').val(value);

					$(form).ajaxSubmit({
					success: function(html){
						var success = $(html).find('.alert-success'),
							errors = $(html).find('.error');
						if(success.length)
						{
							location.reload();
							// success.children('button').remove();
							// O_o.alert(success.text());
						}

						var errors_collection = {},
							errors_text = '';

						$(errors).each(function(){
							var key = $(this).children('label').text(),
								value = $(this).find('.help-inline').text();
							errors_collection[key] = value;
							errors_text += value+'<br>';
						});

						if(errors_text)
							O_o.alert(errors_text, 'danger');
					}
				});
			}
		});
	});