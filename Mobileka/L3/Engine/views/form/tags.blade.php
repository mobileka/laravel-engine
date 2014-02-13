<div class="row-fluid">
	<div class="span11">
		{{ Form::text($name, Input::old($inputOldName, $component->value($lang)), $component->attributes) }}
	</div>
</div>