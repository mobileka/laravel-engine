<?php try { ?>

@foreach ($component->options as $value => $label)
<div class="check-line">
	{{ Form::radio($component->name, $value, Input::old($component->name, $component->value($lang)) == $value, array_merge($component->attributes, array('data-skin' => $component->skin, 'data-color' => $component->color, 'id' => $component->name . $value))) }} {{ Form::label($component->name . $value, $label, array('class' => 'inline')) }}
</div>
@endforeach

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>