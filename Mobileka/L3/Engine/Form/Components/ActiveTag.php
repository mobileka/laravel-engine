<?php namespace Mobileka\L3\Engine\Form\Components;

use Mobileka\L3\Engine\Laravel\Helpers\Misc,
	Mobileka\L3\Engine\Laravel\Str;

class ActiveTag extends BaseComponent {

	protected $template = 'engine::form.activetag';
	protected $htmlElement = 'textarea';

	protected $requiredAttributes = array(
		'class' => 'activetag',
		'multiple' => 'multiple',
		'style' => 'width: 100%;'
	);

	protected $options;
	protected $attachRoute;
	protected $detachRoute;

	public function value($lang = '')
	{
		return $this->row->{$this->name}()->lists('id');
	}

	public function masterKey()
	{
		$relation = $this->row->{$this->name}();
		return $relation->foreign ? : Str::singular($this->row->table()).'_id';
	}

	public function foreignKey()
	{
		$relation = $this->row->{$this->name}();

		return $foreignKey = Misc::propertyValue($relation, 'other')
			?
			: Str::singular($relation->model->table()).'_id'
		;
	}
}
