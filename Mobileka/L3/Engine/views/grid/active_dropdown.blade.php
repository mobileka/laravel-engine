<?php try { ?>

{{ Form::select($component->getName(), $component->options, $component->value($lang), $component->attributes) }}

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>

<script type="text/javascript">
	$('#grid_row_{{ $component->row->id }} select[name={{ $component->name }}').change(function(){
		var value = $(this).val();

		$.ajax({
			url: "{{ route($component->getRoute(), $component->getParams()) }}",
			data: { {{ $component->name }} : value },
			method: "{{ $component->getMethod() }}",
			success: function(result) {
				if (result.status === "success") {
					alertify.success("Изменения сохранены", 3000);
				} else {
					alertify.error("Изменения не удалось сохранить", 3000);
				}
			}
		});
	});
</script>