$(document).ready(function(){

	var filter = $('.form-grid-filters');

	if (filter.children().length > 0){
		var control = $('.filters-control');
		var icon = $('.filters-control i');
		var currentAddress = window.location.pathname;

		function hide(){
			filter.hide();
			localStorage.setItem(currentAddress, 'hidden')
			control.data('condition', 'hidden');
			control.attr('title', 'Показать фильтр');
			icon.attr('class', 'icon-plus');
		}

		function show(){
			filter.show();
			localStorage.setItem(currentAddress, 'visible');
			control.data('condition', 'visible');
			control.attr('title', 'Скрыть фильтр');
			icon.attr('class', 'icon-minus');
		}

		if (localStorage.getItem(currentAddress) === 'visible') show();
		else if (localStorage.getItem(currentAddress) === 'hidden') hide();

		control.click(function(){
			if (control.data('condition') === 'visible') hide();
			else if (control.data('condition') === 'hidden') show();
		});
	}

});