<?php try { ?>

{{ Form::hidden($name, Input::old($inputOldName, $component->value($lang))) }}

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>
