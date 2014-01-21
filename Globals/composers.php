<?php

View::composer('_system.layouts.inc.subnav', function($view) {
	$sections = Config::get('menu.sections', array());
	$result = array();
	$acl = Acl::make();

	foreach ($sections as $key => $section)
	{
		$items = Arr::getItem($section, 'items', array());

		foreach ($items as $key => $item)
		{
			if (!$acl->checkByAlias($item['route']))
			{
				unset($section['items'][$key]);
			}
		}

		if (Arr::getItem($section, 'items', array()))
		{
			$result[] = $section;
		}
	}

	$view->shares('sections', $result);
});

View::composer('models::default.widgets.star_rating', function($view) {
	Asset::container('plugins')->add('star-rating', 'css/vendor/star-rating/jquery.rating.css');
	Asset::container('footer_plugins')->add('star-rating', 'js/vendor/star-rating/jquery.rating.pack.js');
});

/**
 * Если в композер передать параметр "method", то в виджете будут
 * выведены именно модели, возвращаемые соответствующим методом.
 */
View::composer('models::default.widgets.swiper_carousel', function($view)
{
	Asset::container('footer_plugins')->add('swiper', 'js/vendor/idangerous.swiper-2.1.min.js');

	/*$method = (isset($view->method) ? $view->method : 'carouselModels');

	$models = Models\Models\Model::$method();

	$view->with('models', $models);*/
});

/*View::composer('models::default.widgets.sidebar_models', function($view)
{
	$method = (isset($view->method) ? $view->method : 'sidebarModels');

	$models = Models\Models\Model::$method();

	$view->with('models', $models);
});
*/
View::composer('models::default.widgets._product_block_inner', function($view)
{
	$image_alias = (isset($view->image_alias) ? $view->image_alias : 'default');

	$view->with('image_alias', $image_alias);
});