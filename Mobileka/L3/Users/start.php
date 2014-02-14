<?php

Bundle::start('engine');

Autoloader::namespaces(array(
	'Users' => Bundle::path('users')
));
