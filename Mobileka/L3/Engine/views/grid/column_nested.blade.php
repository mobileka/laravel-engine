<?php try { ?>

{{ str_repeat('—', ($component->row->level - $component->initialLevel)) }} {{ $component->value() }}

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>