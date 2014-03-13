<?php try { ?>

{{ $component->value($lang) }}

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>