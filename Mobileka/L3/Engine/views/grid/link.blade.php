<?php try { ?>

<div class="text-center">
	<a href="{{{ $component->link }}}" {{ HTML::attributes($component->attributes) }}>{{{ $component->label }}}</a>
</div>

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>
