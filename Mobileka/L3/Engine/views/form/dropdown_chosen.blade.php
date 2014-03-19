<?php try { ?>

<div {{ HTML::attributes($component->parentAttributes) }}>
	{{ Form::select($component->selectboxName(), $component->options, Input::old($component->name, $component->value($lang)), $component->attributes) }}
</div>

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>