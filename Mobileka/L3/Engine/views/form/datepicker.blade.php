<?php
try
{
	$attributes = $component->attributes;
	$attributes['class'] = (isset($component->attributes['class'])) ? $component->attributes['class'] . ' datepick' : 'datepick';
?>

{{ Form::text($component->name, Input::old($component->name, dateTimeToDate($component->value($lang))), $attributes) }}

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>