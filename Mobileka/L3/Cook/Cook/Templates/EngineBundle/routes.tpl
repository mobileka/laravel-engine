<?php

RestfulRouter::make()
	->except('view')
	->resource(array(
		'submodule' => 'admin', 
		'bundle' => '<bundles>',
		'controller' => '<tables>'
	));