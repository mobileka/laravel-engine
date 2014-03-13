<?php try { ?>

<div class="control-group">
	<label class="control-label" for="filter_{{ $component->name }}">
		{{ $component->label }}
	</label>

	<div class="controls">
		<div class="input-prepend">
			<span class="add-on"><i class="icon-search"></i></span>
			{{ Form::select('filters[where]['.$component->name.']', $component->options, $component->value(), array('class' => 'chosen-select')) }}
		</div>
	</div>
</div>

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>