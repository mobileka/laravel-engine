<?php try { ?>

<div id="{{ $component->name }}" data-id="{{{ $component->row->id }}}" class="span12">
	{{ Form::select($component->name, $component->options, Input::old($component->name, $component->value()), $component->attributes) }}
</div>

<script>
	$(function(){
		var masterKey = $('#{{ $component->name }}').data('id');

		$("#{{ $component->name }} select.{{ $component->requiredAttributes['class'] }}").select2({})
			.on('change', function(result)
			{
				if (typeof result.removed === "undefined")
				{
					$.ajax({
						'url': "{{ route($component->attachRoute) }}",
						'type': 'post',
						'data': {
							'{{ $component->masterKey() }}' : masterKey,
							'{{ $component->foreignKey() }}' : result.added.id,
						},
						success: function(data)
						{}
					});
				}
				else
				{
					$.ajax({
						'url': "{{ route($component->detachRoute) }}",
						'type': 'post',
						'data': {
							'_method' : 'delete',
							'{{ $component->masterKey() }}' : masterKey,
							'{{ $component->foreignKey() }}' : result.removed.id,
						},
						success: function(data)
						{}
					});
				}
			});
	});
</script>

<?php } catch(\Exception $e) { exit($e->getMessage()); } ?>