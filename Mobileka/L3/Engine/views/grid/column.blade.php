<?php try { ?>

<?php $value = $component->value($lang); ?>

<span title="{{{ $component->rawValue }}}">{{ $value }}</span>

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>
