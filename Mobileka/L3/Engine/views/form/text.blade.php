<?php try { ?>

{{ $component->prefix }}
{{ Form::text($name, Input::old($inputOldName, $component->value($lang)), $component->attributes) }}
{{ $component->suffix }}

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>
