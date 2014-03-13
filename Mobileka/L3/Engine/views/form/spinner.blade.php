<?php try { ?>

{{ Form::text($component->name, Input::old($component->name, $component->value($lang)), $component->attributes) }}

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>