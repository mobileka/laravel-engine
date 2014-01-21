<?php $checked = ($component->value() == $component->checkedValue ? 'checked="checked"' : ''); ?>

<div class="check-line">
	<input type="checkbox" class="icheck-me-hidden" name="{{ $component->name }}" checked="checked" value="{{ $component->value() == $component->checkedValue ? $component->checkedValue : $component->uncheckedValue }}" data-uncheckedValue="{{ $component->uncheckedValue }}" data-checkedValue="{{ $component->checkedValue }}">
	<input type="checkbox" class="icheck-me icheck-me-trigger" id="{{ $component->name }}" {{ $checked }} data-skin="{{ $component->skin }}" data-color="{{ $component->color }}">
</div>