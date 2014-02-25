<?php namespace Mobileka\L3\Engine\Form\Components;

class BaseUploadComponent extends BaseComponent {

	protected $modelName = null;

	public function getModelName()
	{
		$modelName = is_null($this->modelName) ? get_class($this->row) : $this->modelName;
		return str_replace('\\', '\\\\', $modelName);
	}

}

