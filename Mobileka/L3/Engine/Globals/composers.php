<?php

View::composer('engine::_system.layouts.inc.subnav', function($view) {
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

