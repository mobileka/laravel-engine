<?php try { ?>

{{ Form::password($component->name, $component->attributes) }}

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>