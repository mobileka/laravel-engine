$(document).ready(function(){

	$('.link-submit').click(function(e){
		e.preventDefault();
		$(this).closest('form').submit();
	});

	$('.delete_button').click(function(e){
		e.preventDefault();
		var button = $(this);

		if($(this).hasClass('disabled') && $(this).closest('#expense_grid').length && $(this).closest('tr').hasClass('have_children'))
		{
			O_o.alert('Нельзя удалять запись у которой есть платежи. Начните удаление с первых платежей', 'danger');
		}
		else
		{
			O_o.confirm(
				'Вы действительно хотите удалить?',
				function(){
					button.closest('form').submit();
				}
			);
		}
	});

	$('#eloquent_form').submit(function(){
		$(this).find('input[type=submit]').prop('disabled', true);
	});


	$('.scroll_up').click(function(){
		O_o.scrollTop();
	});

	$('.scroll_down').click(function(){
		O_o.scrollBottom();
	});

	//$('.per_page_changer').show();

	// Validation for warehouse
	$('#warehouses_form, #sales_form').submit(function(){
		var result = false;
		$(this).children('#category_list').find('input').each(function(){
			if($(this).val()!=='')
				result = true;
		});

		if(!result)
			O_o.alert('Необходимо заполнить хотябы одно из полей', 'danger');

		return result;
	});


	// Filter
	$('.filter_showhide').click(function(){
		var icon_show = 'icon-zoom-in',
			icon_hide = 'icon-zoom-out',
			icon = $(this).children('i'),
			form = $('.form-filter');

		if(form.is(':visible'))
		{
			form.slideUp();
			icon.removeClass(icon_hide).addClass(icon_show);
		}
		else
		{
			form.slideDown();
			icon.removeClass(icon_show).addClass(icon_hide);
		}
	});

	var active_tab = {};
	$('.date_group .nav-tabs a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

	$('.date_group .add-on:has(.icon-calendar)').click(function(){
		$(this).closest('.input-append').children('.datepicker').focus();
	});


	$('.form-filter').submit(function(){
		$(this).find('.date_group').each(function(){

			var filter_name = $(this).data('name'),
				filter_type = $(this).find('.nav-tabs')
								.children('.active')
								.children('a').attr('href'),
				period_tab	= $(this).find('#period_'+filter_name),
				input_type	= $(this).find('input[name="filters[type]['+filter_name+']"]'),
				input_from	= period_tab.find('input[name="filters[from]['+filter_name+']"]'),
				input_to	= period_tab.find('input[name="filters[to]['+filter_name+']"]'),
				all_inputs	= $(this).find('input, select')
								.not(input_from)
								.not(input_to)
								.not(input_type),
				period_from = input_from.val(),
				period_to	= input_to.val();


			switch(filter_type){
				// By Day
				case '#by_day_'+filter_name:
					active_tab[filter_name] = 'by_day';

					var date_from = $(this).find(filter_type).find('input[name="filters[by_day]['+filter_name+']"]').val(),
						date_to = date_from;


					period_from = date_from;
					period_to = date_to;
				break;

				// By Month
				case '#by_month_'+filter_name:
					active_tab[filter_name] = 'by_month';
					var month_from = $(this).find(filter_type).find('select[name="filters[month]['+filter_name+']"]').val(),
						year_from  = $(this).find(filter_type).find('select[name="filters[year]['+filter_name+']"]').val();

					if(!month_from && !year_from)
					{
						period_from = '';
						period_to = '';
					}
					else
					{
					
						period_from = year_from + '-' + month_from + '-01';
						period_to = year_from + '-' + month_from + '-31';
					}
				break;

				// By Year
				case '#by_year_'+filter_name:
					active_tab[filter_name] = 'by_year';

					var year_from = $(this).find(filter_type).find('select[name="filters[by_year]['+filter_name+']"]').val(),
						year_to   = parseInt(year_from) + 1;

					if(!year_from)
					{
						period_from = '';
						period_to = '';
					}
					else
					{
						period_from = year_from + '-01-01';
						period_to = year_to + '-01-01';
					}
				break;

				default:
					active_tab[filter_name] = 'by_period';
			}


			// Save values
			input_from.val(period_from);
			input_to.val(period_to);

			if(period_from || period_to)
			{
				input_type.val(active_tab[filter_name]);
			}
			else
			{
				input_type.val('');
			}


			//return false;
			// Clear URL
			$(all_inputs).val('');
		});

		$(this).find('input, select').each(function(){
			if($(this).val() === '')
			{
				$(this).prop('disabled', true);
			}
		});

		//return false; // if something wrong for debug
	});

	$('.reset-filters').click(function(){
		var form = $(this).closest('form');
		form.find('input, select').not('[type=submit],[type=reset]').prop('disabled', true);
		form.find('input.default_value').prop('disabled', false);
		form.submit();
	});

	/* hide window 
	$('body').append('<div id="hideall"></div>');
	$('#hideall').css({position:'fixed', height: '100%', width: '100%', top: 0, left: 0, background: 'black', display: 'none'});
	$('#hideall').slideDown(1200000);
	*/
});