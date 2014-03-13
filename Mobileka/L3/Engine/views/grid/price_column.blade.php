<?php try { ?>

<div class="text-right">
	{{ $component->value() }}
</div>

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>