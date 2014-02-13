{{ Form::open_for_files($crud->action, $crud->method, $crud->attributes) }}

<div class="box box-bordered">

	@foreach ($components as $component)
		<div class="control-group">
			<?php $component->row($crud->model); ?>
			{{ Form::label($component->name, formLang($crud->languageFile, $component->name) . $component->required(), array('class' => 'control-label'), false) }}

			<div class="controls">
				@if ($component->localized)
					@foreach (langs() as $lang)
						<label>
							{{ HTML::image('admin_assets/img/flags/' . $lang . '.png', $lang, array('class' => 'flag')) }}
							{{ $this->validation($errors->get('localized: '.$component->name.'_'.$lang)) }}
							{{ $component->render($lang) }}
						</label>
					@endforeach
				@else
					{{ $this->validation($errors->get($component->name)) }}
					{{ $component->render() }}
				@endif
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
