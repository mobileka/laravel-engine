<?php namespace Mobileka\L3\Engine\Grid\Components;

class ImageColumn extends BaseComponent {

	protected $template = 'crud::grid.image_column';

	public function value()
	{
		return $this->row->image ? $this->row->image->admin_grid_thumb : dummyThumbnail('admin_grid_thumb');
	}

}