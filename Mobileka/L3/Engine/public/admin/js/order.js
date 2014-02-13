/*
*	Attention it is depends by:
*		get.js
*/
$(document).ready(function(){
	$('.order_column').click(function(){
		var params = get_vars(),
			order =	get_val(params,'order[]'),
			column = $(this).attr('rel'),
			url = location.href,
			get_query;

			if(order == column + ':asc')
				params = set_val(params, 'order[]', column + ':desc');
			else
				params = set_val(params, 'order[]', column + ':asc');

			if(url.indexOf('?') == -1)
			{
				url += '?';
			}

			get_query = get_build(params);
			location.href = url.substr(0, url.indexOf('?')+1) + get_query;
	});	
});
