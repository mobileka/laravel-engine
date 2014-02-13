<div class="control-group">
	<label class="control-label" for="filter_{{ $component->name }}">
		{{ $component->label }}
	</label>

	<div class="controls">
		<div class="input-xlarge">
			{{ Form::select('filters[where]['.$component->name.']', $component->options, Input::old($component->name, $component->value($lang)), $component->attributes) }}
		</div>
	</div>
</div>