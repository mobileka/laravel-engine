<?php try { ?>

<div class="control-group">
	<label class="control-label" for="filter_{{ $component->name }}">
		{{ $component->label }}
	</label>

	<div class="controls">
		<div class="input-prepend">
			<span class="add-on"><i class="icon-search"></i></span>
			{{ Form::text('filters[contains]['.$component->name.']', $component->value(), array('id' => 'filter_'.$component->name)) }}
		</div>
	</div>
</div>

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>