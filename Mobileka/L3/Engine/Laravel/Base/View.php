<?php namespace Mobileka\L3\Engine\Laravel\Base;

use Mobileka\L3\Engine\Laravel\Helpers\Misc,
	Mobileka\L3\Engine\Laravel\Helpers\Arr,
	Laravel\Config;

/**
 * @author Armen Markossyan <a.a.markossyan@gmail.com>
 * @version 2.0
 * @todo PHPDoc this class
 */
class View extends \Laravel\View {

	protected $content = '';
	protected $title = '';
	protected $viewData = array();
	protected $viewName = '';
	protected $description = '';
	protected $keywords = '';

	public function renderView($options = array())
	{
		/**
		 * Префиксом для любого title будет служить название проекта
		 * Если кто-то задал руками $this->title, то взять его
		 * Иначе взять из $options['title'], если он задан.
		 * Если ничего не задано, то будет использовано значение $this->title по умолчанию
		 */
		$title = Misc::truthyValue($this->title, Arr::getItem($options, 'title', $this->title));

		$this->viewData = Misc::truthyValue($this->viewData, Arr::getItem($options, 'viewData', $this->viewData));
		$this->viewName = Arr::getItem($options, 'viewName', '');

		$this->data['title'] = $title;
		$this->data['content'] = $this->content($options, $this->viewData);
		$this->shares('viewData', $this->viewData);

		$this->data['description'] = Arr::getItem($options, 'description', $this->description);
		$this->data['keywords'] = Arr::getItem($options, 'keywords', $this->keywords);

		$format = Arr::getItem($options, 'format');

		switch ($format)
		{
			case 'json':
				return \Response::json(\Misc::prepareForJson(Arr::getItem($options, 'data', array())));

			case 'ajax':
				return \Response::json(
					array(
						'status' => 'success',
						'errors' => array(),
						'data' => $this->data['content']->render(),
						'viewData' => $this->viewData
					)
				);
		}
	}

	public function validation(array $errors, $view = 'engine::_system.validation_error')
	{
		$result = '';

		foreach ($errors as $error)
		{
			$result .= View::make($view, compact('error'))->render();
		}

		return $result;
	}

	public function validationErrors($field, $view = 'engine::_system.validation_error')
	{
		return $this->validation($this->data['errors']->get($field), $view);
	}

	protected function content($options = array(), $viewData = array())
	{
		/**
		 * Если $content передали руками, то не надо рендерить вьюху
		 */
		if ($result = Arr::find(array($this->content, Arr::getItem($options, 'content'))))
		{
			return $result;
		}

		$viewName = str_replace('@', '.', Controller::$route['uses']);

		if ($this->viewName)
		{
			$viewName = $this->viewName;
		}
		elseif (isset($options['viewName']))
		{
			$viewName = $options['viewName'];
		}

		return View::make($viewName, $viewData);
	}

	/**
	 * Magic Method for handling dynamic data access.
	 */
	public function __get($key)
	{
		return \Arr::getItem($this->data, $key);
	}
}
