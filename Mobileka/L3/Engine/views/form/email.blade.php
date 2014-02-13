<div class="input-prepend">
	<span class="add-on">@</span>
	{{ Form::text($component->name, Input::old($component->name, $component->value($lang)), $component->attributes) }}
</div>