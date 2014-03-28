<?php
try
{
	$value = Input::old($component->name, $component->value($lang));
	$checked = ($value == $component->checkedValue ? 'checked="checked"' : '');
	$currentValue = $checked ? $component->checkedValue : $component->uncheckedValue;
?>


<div class="check-line">
	<input type="hidden" class="checkbox-value" name="{{ $name }}" value="{{ $currentValue }}" data-unchecked-value="{{ $component->uncheckedValue }}" data-checked-value="{{ $component->checkedValue }}">
	<input type="checkbox" class="icheck-me icheck-me-trigger" {{ $checked }} data-skin="{{ $component->skin }}" data-color="{{ $component->color }}">
</div>

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>
