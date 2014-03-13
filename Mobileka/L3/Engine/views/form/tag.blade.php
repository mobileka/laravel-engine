<?php try { ?>

<div class="span12">
	{{ Form::text($component->name, Input::old($component->name, $component->value()), $component->attributes) }}
</div>

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>