<?php namespace Mobileka\L3\Engine\Form\Components;

class CKEditor extends BaseComponent {
	protected $template = 'engine::form.ckeditor';
	protected $htmlElement = 'textarea';
	protected $escape = false;
	protected $nl2br = false;
}