{{ Form::open(URL::to_route(Controller::$route['alias'], Controller::$route['parameters']), 'GET', array('class' => 'form-horizontal form-grid-filters')) }}

@if ($crud->filters)
	@foreach ($crud->model->_order() as $order)
		{{ Form::hidden('order[]', $order) }}
	@endforeach

	@foreach ($crud->filters as $filter)
		{{ $filter->filters($crud->model->conditions())->label(filterLang($crud->languageFile, $filter->name))->render() }}
	@endforeach

{{ Form::submit(___($crud->languageFile, 'filter'), array('class' => 'btn btn-primary')) }}
{{ HTML::link_to_route(Controller::$route['alias'], ___($crud->languageFile, 'clear'), Controller::$route['parameters'], array('class' => 'btn btn-orange')) }}
{{ Form::close() }}

@endif