<?php try { ?>

{{ Form::textarea($name, Input::old($inputOldName, $component->value($lang)), $component->attributes) }}

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>
