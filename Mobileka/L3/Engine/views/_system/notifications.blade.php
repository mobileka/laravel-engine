<?php use Mobileka\L3\Engine\Laravel\Helpers\Arr; ?>

@foreach ($permittedMessageTypes as $type)
	@if ($message = Arr::getItem($notifications, $type.$id))
		<script>
			alertify.{{ $type }}("{{ $message }}", 3000);
		</script>
	@endif
@endforeach
