<?php try { ?>

@if ($component->raw)
{{ $component->value($lang) }}
@else
{{ nl2br(HTML::entities($component->value($lang))) }}
@endif

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>
