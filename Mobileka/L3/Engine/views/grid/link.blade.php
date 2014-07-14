<?php try { ?>

<div class="text-center">
	<a href="{{{ $component->link }}}" {{ HTML::attributes($component->attributes) }}>{{ $component->beforeLabel }}{{ $component->label }}{{ $component->afterLabel }}</a>
</div>

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>
