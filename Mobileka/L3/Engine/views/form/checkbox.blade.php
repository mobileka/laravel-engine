<?php
$checked = ($component->value($lang) == $component->checkedValue ? 'checked="checked"' : '');
//$id = $name;
$id = str_replace(array('[', ']'), '_', $name);
?>


<div class="check-line">
	<input type="checkbox" class="icheck-me-hidden" name="{{ $name }}" checked="checked" value="{{ $component->value($lang) == $component->checkedValue ? $component->checkedValue : $component->uncheckedValue }}" data-uncheckedValue="{{ $component->uncheckedValue }}" data-checkedValue="{{ $component->checkedValue }}">
	<input type="checkbox" class="icheck-me icheck-me-trigger" id="{{ $id }}" {{ $checked }} data-skin="{{ $component->skin }}" data-color="{{ $component->color }}">
</div>
