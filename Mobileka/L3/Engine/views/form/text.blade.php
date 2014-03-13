<?php try { ?>

{{ Form::text($name, Input::old($inputOldName, $component->value($lang)), $component->attributes) }}

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>
