<?php

$attributes = $component->attributes;
$attributes['class'] = (isset($component->attributes['class'])) ? $component->attributes['class'] . ' datepick' : 'datepick';

?>

{{ Form::text($component->name, Input::old($component->name, dateTimeToDate($component->value($lang))), $attributes) }}