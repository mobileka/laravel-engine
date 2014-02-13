$(document).ready(function(){

	$('.print').click(function(e){
		e.preventDefault();

		var	opt = $(this);
			url = opt.attr('href'),
			title = opt.data('title'),
			month = opt.data('month'),
			year = opt.data('year');
			
		win_width = window.outerWidth;
		win_height = window.outerHeight;
		print_window = window.open(url, '_blank', 'width=' + win_width + ',height=' + win_height);
		owner_document = print_window.opener.document;
		print_document = print_window.document;
		
		if(!url)
		{

			print_document.write('<link href="/css/bootstrap.min.css" media="all" type="text/css" rel="stylesheet">');
			print_document.write('<link href="/css/style.css" media="all" type="text/css" rel="stylesheet">');
			print_document.write('<link href="/css/print.css" media="all" type="text/css" rel="stylesheet">');
			print_document.write('<div class="print_tab"><a class="btn" id="send_to_printer"><i class="icon-print"></i> Отправить на печать</a></div>');
			print_document.write('<div class="box-title"><h3>'+title+'</h3></div>');
			print_document.write('<div class="summary_filter">Дата: ' + month + '-' + year + '</div>');
			print_document.write($(owner_document).find('.box-content').html());

			//$(print_document).find('.actions').remove();
			//$(print_document).find('.actions_header').remove();
			$(print_document).find('a').not('#send_to_printer').each(function(){
				$(this).after($(this).text());
				$(this).remove();
			});

			$(print_document).find('#send_to_printer').click(function(){
				$(print_document).find('.print_tab').hide();
				print_window.print();
			});
		}

		print_window.focus();


		
	});


});

