<div class="row-fluid">
	<div class="span3 offset4">

		<h2>{{ $crud->customData['title'] }}</h2>

		{{ Form::open($crud->actionUrl, $crud->method, $crud->attributes) }}
		{{ Form::token() }}

		<div class="box box-bordered">

			@foreach ($components as $component)
				<div class="control-group">
					<?php $component->row($crud->model); ?>
					{{ Form::label($component->name, formLang($crud->languageFile, $component->name) . $component->required(), array('class' => 'control-label'), false) }}

					<div class="controls">
						{{ $this->validation($errors->get($component->name)) }}
						{{ $component->render() }}
					</div>
				</div>
			@endforeach

			{{ Form::hidden('successUrl', $crud->successUrl) }}

			<div class="form-actions">

				@if ($crud->cancelUrl)
					{{ HTML::link($crud->cancelUrl, ___($crud->languageFile, 'cancel'), array('class' => 'btn')) }}
				@endif

				{{ Form::submit(___($crud->languageFile, 'save'), array('class' => 'btn btn-green')) }}

			</div> <!-- .form-actions -->

		</div> <!-- .box.box-bordered -->

		{{ Form::close() }}

	</div> <!-- .span8 -->
</div> <!-- .row-fluid -->