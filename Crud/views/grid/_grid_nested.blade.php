@foreach ($categories as $row)
	<tr>

	@foreach ($components as $component)
		<td>{{ $component->row($row)->render() }}</td>
	@endforeach

	<td>
		{{-- Подробнее (view / show) --}}
		{{ HTML::view_button($row->id) }}

		{{-- Редактировать (edit) --}}
		{{ HTML::edit_button($row->id) }}

		{{-- Удалить (destroy) --}}
		{{-- HTML::destroy_button(URL::to_route($crud->requestId . '_destroy', $row->id), array('class' => 'btn btn-red')); --}}
		{{ HTML::delete_button(URL::to_route(Router::requestId(Controller::$route, 'destroy'), $row->id)) }}
	</td>

	</tr>

	@if (isset($row->children) && $row->children)
		{{ show_tree($row->children, $components) }}
	@endif
@endforeach