<?php try { ?>

{{ str_repeat('—', $component->row->level) }} {{ $component->value() }}

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>