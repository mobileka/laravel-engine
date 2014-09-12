@include('engine::filters')

{{-- Paginator --}}
<div class="text-center">
	{{ $crud->items->links() }}
</div> <!-- .text-center -->

{{-- Ссылка "создать" --}}
<div class="pull-right">
	{{ HTML::link_to_existing_route($crud->requestId . '_add', ___($crud->languageFile, 'add'), array(), array('class' => 'btn btn-teal crud-add-button')) }}
</div>

<table class="table table-hover table-nomargin table-striped">
	<thead>
		<tr>
			@foreach ($components as $heading => $value)
				@if ($value->active and !$value->relevantActions or in_array(Controller::$route['action'], $value->relevantActions))
					<th>
						@if (in_array($heading, $crud->sortable))
						<a href="#" data-order="{{ $value->name }}" class="th-sortable">
							{{ gridLang($crud->languageFile, $heading) }}
						</a>
						@else
							{{ gridLang($crud->languageFile, $heading) }}
						@endif
					</th>
				@endif
			@endforeach
				<th></th>
		</tr>
	</thead>
	<tbody>
		{{-- Содержание таблицы --}}
		@forelse ($crud->items->results as $row)
			<tr>
				@foreach ($components as $component)
					@if ($component->active and !$component->relevantActions or in_array(Controller::$route['action'], $component->relevantActions))
						<td id="grid_row_{{ $row->id }}">{{ $component->row($row)->render($component->localized) }}</td>
					@endif
				@endforeach

				<td class="table-actions">
					{{-- Подробнее (view / show) --}}
					{{ HTML::view_button($row->id) }}

					{{-- Клонировать (clone) --}}
					{{ HTML::clone_button($row->id) }}

					{{-- Редактировать (edit) --}}
					{{ HTML::edit_button($row->id) }}

					{{-- Удалить (destroy) --}}
					{{ HTML::delete_button(URL::to_existing_route(Router::requestId(Controller::$route, 'destroy'), $row->id, 'DELETE'), array('data-label' => $row->getLabel(), 'data-confirm_message' => $crud->deleteConfirmationMessage($row))) }}
				</td>
			</tr>
		@empty
			<tr>
				<td colspan="24">
					<div class="text-center">
						{{ ___($crud->languageFile, 'no_records_found') }}
					</div>
				</td>
			</tr>
		@endforelse
	</tbody>

</table>

{{-- Delete Confirmation Form --}}
<div class="modal hide fade delete" id="delete_modal">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3>{{ ___($this->languageFile, 'delete_confirmation'); }}</h3>
	</div>

	<div class="modal-body">
		<p class='modal-confirm-message'></p>
	</div>

	<div class="modal-footer">
		{{ Form::open('', 'DELETE', array('id' => 'crud_delete_form')) }}
		{{ Form::token() }}
			<a data-dismiss="modal" href="#delete_modal" class="btn">{{ ___($crud->languageFile, 'cancel') }}</a>
			<input type="submit" class="btn btn-danger" value="{{ ___($crud->languageFile, 'labels.delete') }}">
		{{ Form::close() }}
	</div>
</div>

{{-- Paginator --}}
<div class="text-center">
	{{ $crud->items->links() }}
</div> <!-- .text-center -->
