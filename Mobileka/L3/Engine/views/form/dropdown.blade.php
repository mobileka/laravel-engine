<?php try { ?>

{{ Form::select($component->selectboxName(), $component->options, Input::old($component->name, $component->value($lang)), $component->attributes) }}

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>