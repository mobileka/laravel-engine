<div class="input-xlarge">
	{{ Form::select($component->selectboxName(), $component->options, Input::old($component->name, $component->value($lang)), $component->attributes) }}
</div>