@if ($crud->filters)
	<p class="text-right">
		<a href="#" class="btn btn-primary filters-control" title="Скрыть фильтр" data-condition="visible" onClick="return false;">
			<i class="icon-minus"></i>
		</a>
	</p>
@endif

{{ Form::open(URL::to_route(Controller::$route['alias'], Controller::$route['parameters']), 'GET', array('class' => 'form-horizontal form-grid-filters', 'style' => '')) }}
@foreach ($crud->model->_order() as $order)
	{{ Form::hidden('order[]', $order) }}
@endforeach

@if ($crud->filters)
	@foreach ($crud->filters as $filter)
		{{ $filter->filters($crud->model->conditions())->label(filterLang($crud->languageFile, $filter->name))->render() }}
	@endforeach

{{ Form::submit(___($crud->languageFile, 'filter'), array('class' => 'btn btn-primary')) }}
{{ HTML::link_to_route(Controller::$route['alias'], ___($crud->languageFile, 'clear'), Controller::$route['parameters'], array('class' => 'btn btn-orange')) }}
@endif

{{ Form::close() }}
