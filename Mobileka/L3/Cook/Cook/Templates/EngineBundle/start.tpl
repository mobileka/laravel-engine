<?php

Autoloader::namespaces(array(
	'<Bundles>' => Bundle::path('<bundles>')
));

IoC::register('<Table>Model', function()
{
	return new <Bundles>\Models\<Table>;
});

require 'Crud.php';
