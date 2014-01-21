<div class="input-xlarge">
	{{ Form::select($component->selectboxName(), $component->options, Input::old($component->name, $component->value()), $component->attributes) }}
</div>