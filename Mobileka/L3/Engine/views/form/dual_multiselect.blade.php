<?php try { ?>

{{ Form::select($component->selectboxName(), $component->options, Input::old($component->name, $component->value($lang)), array_merge($component->attributes, array('id' => uniqid()))) }}

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>
