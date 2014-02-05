<?php use \Helpers\Arr; ?>

@foreach ($permittedMessageTypes as $type)
	@if ($message = Arr::getItem($notifications, $type))
		<script>
			alertify.{{ $type }}("{{ $message }}", 3000);
		</script>
	@endif
@endforeach