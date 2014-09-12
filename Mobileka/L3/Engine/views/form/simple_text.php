<?php try { ?>

<p>{{ $component->value($lang) }}</p>

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>
