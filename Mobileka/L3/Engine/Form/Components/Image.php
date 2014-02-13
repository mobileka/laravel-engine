<?php namespace Mobileka\L3\Engine\Form\Components;

class Image extends BaseComponent {

	protected $template = 'engine::form.image';
	protected $div_class = 'fileupload-featured_image';
	protected $jcropParams = array();

	/**
	 * Sets the parameters to be applied to the Jcrop plugin
	 * They are written to the JS object, so these support
	 * all the parameters that JCrop supports.
	 *
	 * Keys of the array should be the param names,
	 * and values should correspond to the param values,
	 * like so:
	 *
	 * array('bgOpacity' => '.4', 'setSelect' => '[ 100, 100, 50, 50 ]')
	 *
	 * They can be found here:
	 * http://deepliquid.com/content/Jcrop_Manual.html#Setting_Options
	 *
	 * @param  array  $jcropParams
	 * @return Image
	 */
	public function jcropParams($jcropParams = array())
	{
		$this->jcropParams = $jcropParams;

		return $this;
	}
}
