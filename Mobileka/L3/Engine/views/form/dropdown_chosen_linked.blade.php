<?php try { ?>

<?php Asset::container('engine_assets')->add('linked_list', 'bundles/engine/admin/js/linked_list.js') ?>

<div {{ HTML::attributes($component->parentAttributes) }}>
	{{ Form::select($component->selectboxName(), $component->options, Input::old($component->name, $component->value($lang)), $component->attributes) }}
</div>

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>

@if (!isset($component->attributes['disabled']))

	<script>
		app.current = {};

		@foreach ($component->row->attributes as $name => $attribute)

			@if ($attribute and isset($component->linked_items[$name]))

				app.current.{{ $name }} = '{{ $attribute }}';

			@endif

		@endforeach

		app.linked_items    = {{ json_encode($component->linked_items) }};
		app.selectorPrefix  = 'select[name="',
		app.selectorPostfix = '"]';

	</script>

@endif