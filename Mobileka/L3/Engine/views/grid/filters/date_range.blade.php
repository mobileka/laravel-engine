<?php try { ?>

<div class="control-group">
	<label class="control-label" for="filter_{{ $component->name }}">
		{{ $component->label }}
	</label>

	<div class="controls">
		<div class="input-prepend">
			<span class="add-on"><i class="icon-search"></i></span>
			{{ Form::text('filters[from]['.$component->from.']', $component->fromValue(), array('id' => 'filter_'.$component->from, 'class' => 'datepick', 'placeholder' => 'С')) }}
		</div>

		<div class="input-prepend">
			<span class="add-on"><i class="icon-search"></i></span>
			{{ Form::text('filters[to]['.$component->to.']', $component->toValue(), array('id' => 'filter_'.$component->to, 'class' => 'datepick', 'placeholder' => 'По')) }}
		</div>
	</div>
</div>

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>