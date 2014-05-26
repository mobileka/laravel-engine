<?php
$languages = langs();
$localized = $common = array();
foreach ($components as $fieldName => $component) {
	if ($component->active and !$component->relevantActions or in_array(Controller::$route['action'], $component->relevantActions)) {
		$component->row($crud->model);
		if ($component->localized) {
			$localized[$fieldName] = $component;
		} else {
			$common[$fieldName] = $component;
		}
	}
}
$activeLanguage = $languages[0];
?>

{{ Form::open_for_files($crud->actionUrl, $crud->method, $crud->attributes) }}
{{ Form::token() }}

<ul class="nav nav-tabs">
	@foreach ($languages as $language)
		<li class="{{ $language === $activeLanguage ? 'active' : '' }}"><a href="#tab_{{ $language }}" data-toggle="tab"> {{ $language }}</a></li>
	@endforeach
</ul>

<div class="box box-bordered">
	<div class="tab-content">
		@foreach ($languages as $language)
		<div class="tab-pane {{ $language === $activeLanguage ? 'active' : '' }}" id="tab_{{ $language }}">
			@foreach ($localized as $fieldName => $component)
			<div id="row_{{$fieldName}}" class="control-group">
				<label for="{{ $component->name }}" class="control-label">{{ formLang($crud->languageFile, $component->name) . $component->required() }}</label>
				<div class="controls {{ $component->parentClass ? : '' }}">
					<label>
						{{ $this->validation($errors->get('localized: '.$component->name.'_'.$language)) }}
						{{ $component->render($language) }}
					</label>
				</div>
			</div>
			@endforeach

			@if ($language === $activeLanguage)
			{{-- Render common components in the first tab --}}
				@foreach ($common as $fieldName => $component)
					@if ($component->isHidden())
						{{ $this->validation($errors->get($component->name)) }}
						{{ $component->render() }}
					@else
						<div id="row_{{$fieldName}}" class="control-group">
							<label for="{{ $component->name }}" class="control-label">{{ formLang($crud->languageFile, $component->name) . $component->required() }}</label>
							<div class="controls {{ $component->parentClass ? : '' }}">
								{{ $this->validation($errors->get($component->name)) }}
								{{ $component->render() }}
							</div>
						</div>
					@endif
				@endforeach
			@endif
		</div>
		@endforeach
	</div>


	{{ Form::hidden('successUrl', $crud->successUrl) }}

	<div class="form-actions">

		@if ($crud->cancelUrl)
			{{ HTML::link($crud->cancelUrl, ___($crud->languageFile, 'cancel'), array('class' => 'btn')) }}
		@endif

		{{ Form::submit(___($crud->languageFile, 'save'), array('class' => 'btn btn-green')) }}


	</div> <!-- .form-actions -->

</div> <!-- .box.box-bordered -->

{{ Form::close() }}
