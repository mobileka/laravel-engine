<?php try { ?>

<?php Asset::container('engine_assets')->add('linked_list', 'bundles/engine/admin/js/linked_list.js') ?>

<div class="control-group">
	<label class="control-label" for="filter_{{ $component->name }}">
		{{ $component->label }}
	</label>

	<div class="controls">
		<div {{ HTML::attributes($component->parentAttributes) }}>
			{{ Form::select('filters[where]['.$component->name.']', $component->options, Input::old($component->name, $component->value($lang)), $component->attributes) }}
		</div>
	</div>
</div>

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>

@if (!isset($component->attributes['disabled']))

	<script>
		app.current = {};

		@foreach (Input::get('filters.where', array()) as $name => $attribute)

			@if ($attribute and isset($component->linked_items[$name]))
				app.current.{{{ $name }}} = '{{{ $attribute }}}';
			@endif

		@endforeach

		app.linked_items    = {{ json_encode($component->linked_items) }};
		app.selectorPrefix  = 'select[name="filters[where][',
		app.selectorPostfix = ']"]';
	</script>

@endif