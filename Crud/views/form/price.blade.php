<div class="input-append">
	{{ Form::text($component->name, Input::old($component->name, $component->value()), $component->attributes) }}
	<span class="add-on">〒</span>
</div>