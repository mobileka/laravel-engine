<?php namespace Mobileka\L3\Engine\Form\Components;

class CKEditor extends TextArea {
	protected $htmlElement = 'textarea';
	protected $nl2br = false;
	protected $requiredAttributes = array('class' => 'ckeditor');
}