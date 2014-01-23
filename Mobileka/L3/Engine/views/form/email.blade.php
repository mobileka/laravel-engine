<div class="input-prepend">
	<span class="add-on">@</span>
	{{ Form::text($component->name, Input::old($component->name, $component->value()), $component->attributes) }}
</div>