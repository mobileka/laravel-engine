<?php try { ?>

<div class="control-group">
	<label class="control-label" for="filter_{{ $component->name }}">
		{{ $component->label }}
	</label>

	<div class="controls">
		<div class="input-prepend">
			<span class="add-on"><i class="icon-search"></i></span>
			{{ Form::text(null, $component->value(), array('id' => 'filter_' . $component->name, 'class' => 'datepick')) }}

			{{ Form::hidden('filters[from]['.$component->from.']', $component->fromValue(), array('id' => 'filter_'.$component->from . '_from')) }}
			{{ Form::hidden('filters[to]['.$component->to.']', $component->toValue(), array('id' => 'filter_'.$component->to . '_to')) }}
		</div>
	</div>
</div>

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>