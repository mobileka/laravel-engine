<?php
use Mobileka\L3\Engine\Form\Components\TextArea;

try
{

$attributes = $component->attributes;
$attributes['class'] = ($class = Arr::getItem($component->attributes, 'class')) ? $class . ' ckeditor' : 'ckeditor';
?>

{{ TextArea::make($component->name, $attributes)->localized($component->localized)->row($component->row)->render($lang) }}

<?php } catch (Exception $e) { exit($e->getMessage()); } ?>
