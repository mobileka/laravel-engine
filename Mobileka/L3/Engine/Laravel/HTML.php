<?php namespace Mobileka\L3\Engine\Laravel;

use Mobileka\L3\Engine\Laravel\Helpers\Arr;
use Mobileka\L3\Engine\Laravel\Base\Controller;

class HTML extends \Laravel\HTML
{
    /**
     * Build a list of HTML attributes from an array.
     *
     * @param  array  $attributes
     * @return string
     */
    public static function attributes($attributes)
    {
        $html = array();

        foreach ((array) $attributes as $key => $value) {
            // For numeric keys, we will assume that the key and the value are the
            // same, as this will convert HTML attributes such as "required" that
            // may be specified as required="required", etc.
            if (is_numeric($key)) {
                $key = $value;
            }

            if (! is_null($value)) {
                $value = is_array($value) ? implode(' ', $value) : $value;
                $html[] = $key.'="'.static::entities($value).'"';
            }
        }

        return (count($html) > 0) ? ' '.implode(' ', $html) : '';
    }

    /**
     * Generate an HTML link to a route if it exists.
     *
     * An array of parameters may be specified to fill in URI segment wildcards.
     *
     * <code>
     *		// Generate a link to the "profile" named route
     *		echo HTML::link_to_route('profile', 'Profile');
     *
     *		// Generate a link to the "profile" route and add some parameters
     *		echo HTML::link_to_route('profile', 'Profile', array('taylor'));
     * </code>
     *
     * @param  string $name
     * @param  string $title
     * @param  array  $parameters
     * @param  array  $attributes
     * @param  bool   $entities   To escape or not to escape
     * @return string
     */
    public static function link_to_existing_route($name, $title = null, $parameters = array(), $attributes = array(), $entities = true)
    {
        $aliases = array_pluck(array_values(Router::$routes['GET']), 'as');

        if (Router::exists($name) and Acl::make()->checkByAlias($name)) {
            return static::link_to_route($name, $title, $parameters, $attributes, $entities);
        }

        return '';
    }

    /**
     * Generate an HTML link to a route.
     *
     * An array of parameters may be specified to fill in URI segment wildcards.
     *
     * <code>
     *		// Generate a link to the "profile" named route
     *		echo HTML::link_to_route('profile', 'Profile');
     *
     *		// Generate a link to the "profile" route and add some parameters
     *		echo HTML::link_to_route('profile', 'Profile', array('taylor'));
     * </code>
     *
     * @param  string $name
     * @param  string $title
     * @param  array  $parameters
     * @param  array  $attributes
     * @param  bool   $entities   To escape or not to escape
     * @return string
     */
    public static function link_to_route($name, $title = null, $parameters = array(), $attributes = array(), $entities = true)
    {
        return static::link(URL::to_route($name, $parameters), $title, $attributes, null, $entities);
    }

    /**
     * Generate a HTML link.
     *
     * <code>
     *		// Generate a link to a location within the application
     *		echo HTML::link('user/profile', 'User Profile');
     *
     *		// Generate a link to a location outside of the application
     *		echo HTML::link('http://google.com', 'Google');
     * </code>
     *
     * @param  string $url
     * @param  string $title
     * @param  array  $attributes
     * @param  bool   $https
     * @param  bool   $entities   To escape or not to escape
     * @return string
     */
    public static function link($url, $title = null, $attributes = array(), $https = null, $entities = true)
    {
        $url = URL::to($url, $https);

        if (is_null($title)) {
            $title = $url;
        }

        $title = ($entities ? static::entities($title) : $title);

        return '<a href="'.$url.'"'.static::attributes($attributes).'>'.$title.'</a>';
    }

    public static function destroy_button($url, $attributes = array(), $languageFile = 'default', $template = 'engine::grid._destroy_button')
    {
        return View::make(
            $template,
            array(
                'url' => $url,
                'attributes' => $attributes,
                'languageFile' => $languageFile
            )
        );
    }

    protected static function parse_params($params, $action)
    {
        $params = array($params);

        $route = Arr::getItem($params, 'route', Router::requestId(Controller::$route, $action));
        unset($params['route']);

        return array($route, $params);
    }

    public static function clone_button($params, $languageFile = 'engine::default')
    {
        list($route, $params) = static::parse_params($params, 'clone');

        return HTML::link_to_existing_route($route, '<i class="icon-copy"></i>', $params, array('title' => ___($languageFile, 'clone'), 'class' => 'crud-view-button btn btn-orange'), false);
    }

    public static function view_button($params = array(), $languageFile = 'engine::default')
    {
        list($route, $params) = static::parse_params($params, 'view');

        return HTML::link_to_existing_route($route, '<i class="icon-eye-open"></i>', $params, array('title' => ___($languageFile, 'view'), 'class' => 'crud-view-button btn btn-darkblue'), false);
    }

    public static function edit_button($params = array(), $languageFile = 'engine::default')
    {
        list($route, $params) = static::parse_params($params, 'edit');

        return HTML::link_to_existing_route($route, '<i class="icon-edit"></i>', $params, array('title' => ___($languageFile, 'edit'), 'class' => 'crud-edit-button btn btn-darkblue'), false);
    }

    public static function edit_filenames_button($params = array(), $languageFile = 'default')
    {
        list($route, $params) = static::parse_params($params, 'edit_filenames');

        return HTML::link_to_existing_route($route, '<i class="icon-font"></i>', $params, array('title' => ___($languageFile, 'edit_filenames'), 'class' => 'crud-edit-button btn btn-darkblue'), false);
    }

    public static function delete_button($delete_url, $attributes = array())
    {
        $attributes = array_merge(
            array(
                'title' => ___('default', 'destroy'),
                'class' => 'btn btn-red delete-toggle',
                'data-url' => $delete_url
            ),
            $attributes
        );

        return ($delete_url and $delete_url !== '#')
            ? HTML::link('#', '<i class="icon-remove-sign"></i>', $attributes, null, false)
            : ''
        ;
    }
}
