<div class="input-append">
	{{ Form::text($component->name, Input::old($component->name, $component->value($lang)), $component->attributes) }}
	<span class="add-on">〒</span>
</div>