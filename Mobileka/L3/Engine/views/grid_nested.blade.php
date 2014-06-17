@include('engine::filters')

<div class="modal hide fade" id="delete_modal">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h3>{{ $crud->deleteConfirmationMessage() }}</h3>
	</div>

	<div class="modal-body">
		<p>{{ ___($crud->languageFile, 'delete_confirmation') }}</p>
	</div>

	<div class="modal-footer">
		{{ Form::open('', 'DELETE', array('id' => 'crud_delete_form')) }}
			<a data-toggle="modal" href="#delete_modal" class="btn">{{ ___($crud->languageFile, 'cancel') }}</a>
			<input type="submit" class="btn btn-danger" value="{{ ___($crud->languageFile, 'labels.delete') }}">
		{{ Form::close() }}
	</div>
</div>

{{-- Ссылка "создать" --}}
<div class="pull-right">
	{{ HTML::link_to_existing_route($crud->requestId . '_add', ___($crud->languageFile, 'add'), array(), array('class' => 'btn btn-teal crud-add-button')) }}
</div>

<table class="table table-hover table-nomargin table-striped">
	<thead>
		<tr>
			@foreach ($components as $heading => $value)
				<th>{{ gridLang($crud->languageFile, $heading) }}</th>
			@endforeach
				<th></th>
		</tr>
	</thead>
	<tbody>
		@if ($crud->items)
			{{ show_tree($crud->items, $components) }}
		@else
			{{ ___($crud->languageFile, 'no_records_found') }}
		@endif
	</tbody>

</table>
