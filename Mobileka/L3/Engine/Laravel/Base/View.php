<?php namespace Mobileka\L3\Engine\Laravel\Base;

use Mobileka\L3\Engine\Laravel\Helpers\Misc;
use Mobileka\L3\Engine\Laravel\Helpers\Arr;
use Mobileka\L3\Engine\Laravel\Config;

/**
 * @author Armen Markossyan <a.a.markossyan@gmail.com>
 * @version 2.0
 * @todo PHPDoc this class
 */
class View extends \Laravel\View
{
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
         */
        if ($projectName = Config::get('application.project_name', '')) {
            $projectName .= ' ';
        }

        /**
		 * Если кто-то задал руками $this->title, то взять его
		 * Иначе взять из $options['title'], если он задан.
		 * Если ничего не задано, то будет использовано значение $this->title по умолчанию
		 */
        $this->data['title'] = Misc::truthyValue($this->title, Arr::getItem($options, 'title', $this->title));

        $this->viewData = Misc::truthyValue($this->viewData, Arr::getItem($options, 'viewData', $this->viewData));
        $this->viewName = Arr::getItem($options, 'viewName', '');

        $this->data['content'] = $this->content($options, $this->viewData);
        $this->shares('viewData', $this->viewData);

        $this->data['description'] = Arr::getItem($options, 'description', $this->description);
        $this->data['keywords'] = Arr::getItem($options, 'keywords', $this->keywords);

        $format = Arr::getItem($options, 'format');

        switch ($format) {
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

            case 'pdf':
                if (!class_exists('mPDF')) {
                    throw new \Exception('You need to add "mpdf/mpdf": "v6.0-beta" to your composer.json file and run "composer update"');
                }

                error_reporting(0); // Without this mPDF might throw a fatal error

                $header = '<div style="font-size: 10px; line-height: 20px">' . ___('front', 'site_title') . '</div>';
                $html   = static::make($this->data['content']->view, $this->data['content']->data)->render();
                $title  = \Str::transliterate((isset($options['viewData']['item']) ? $options['viewData']['item']->name : $options['title']));

                $mpdf = new \mPDF;
                $mpdf->SetHTMLHeader($header);
                $mpdf->SetFooter('{PAGENO}');
                $mpdf->WriteHTML($html);
                $mpdf->Output($title, 'D');

                exit;
        }
    }

    public function validation(array $errors, $view = 'engine::_system.validation_error')
    {
        $result = '';

        foreach ($errors as $error) {
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
        if ($result = Arr::find(array($this->content, Arr::getItem($options, 'content')))) {
            return $result;
        }

        $viewName = str_replace('@', '.', Controller::$route['uses']);

        if ($this->viewName) {
            $viewName = $this->viewName;
        } elseif (isset($options['viewName'])) {
            $viewName = $options['viewName'];
        }

        return View::make($viewName, $viewData);
    }

    public function __set($key, $value)
    {
        $this->data[$key] = $value;
        $this->$key = $value;
    }

    /**
	 * Magic Method for handling dynamic data access.
	 */
    public function __get($key)
    {
        return Arr::getItem($this->data, $key);
    }
}
