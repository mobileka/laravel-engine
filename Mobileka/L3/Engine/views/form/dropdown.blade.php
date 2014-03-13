<?php try { ?>

{{ Form::select($component->name, $component->options, Input::old($component->name, $component->value($lang)), $component->attributes) }}

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>