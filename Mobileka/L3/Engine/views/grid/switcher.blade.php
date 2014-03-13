<?php try { ?>

{{ Form::checkbox($component->name, $component->checkedValue, $component->value() == $component->checkedValue, array_merge($component->attributes, array('data-url' => $component->url, 'data-checkedValue' => $component->checkedValue, 'data-uncheckedValue' => $component->uncheckedValue, 'data-skin' => $component->skin, 'data-color' => $component->color))) }}

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>