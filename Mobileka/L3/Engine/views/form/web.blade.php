<?php try { ?>

<div class="input-prepend">
	<span class="add-on"><i class="glyphicon-globe_af"></i></span>
	{{ Form::text($component->name, Input::old($component->name, $component->value()), $component->attributes) }}
</div>

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>