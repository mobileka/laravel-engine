{{ Form::open_for_files($crud->actionUrl, $crud->method, $crud->attributes) }}
{{ Form::token() }}
<div class="box box-bordered">
	@foreach ($components as $fieldName => $component)
		@if ($component->active and !$component->relevantActions or in_array(Controller::$route['action'], $component->relevantActions))
			<?php $component->row($crud->model); ?>
			@if ($component->isHidden())
				@if ($component->localized)
					@foreach (langs() as $lang)
						{{ $this->validation($errors->get('localized: '.$component->name.'_'.$lang)) }}
						{{ $component->render($lang) }}
					@endforeach
				@else
					{{ $this->validation($errors->get($component->name)) }}
					{{ $component->render() }}
				@endif
			@else
				<div id="row_{{$fieldName}}" class="control-group">
					<label for="{{ $component->name }}" class="control-label">{{ formLang($crud->languageFile, $component->name) . $component->required() }}</label>
					<div class="controls {{ $component->parentClass ? : '' }}">
						@if ($component->localized)
							@foreach (langs() as $lang)
								<label>
									{{-- HTML::image('admin_assets/img/flags/' . $lang . '.png', $lang, array('class' => 'flag')) --}}
									{{ $lang }}&nbsp;
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
			@endif
		@endif
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
