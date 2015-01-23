<?php

define('Q', '\'');

define('QQ', '"');

define('T', "\t");

Autoloader::namespaces(array(
	'Cook' => Bundle::path('cook').'Cook',
));

IoC::singleton('Constructor', function()
{
	return new Cook\Constructor;
});

IoC::register('Replacer', function()
{
	return new Cook\Replacer;
});

IoC::register('Template', function()
{
	return new Cook\Template;
});

IoC::register('Generator', function()
{
	return new Cook\Generator;
});

IoC::register('task: migrate', function()
{
	$database = new Laravel\CLI\Tasks\Migrate\Database;
	$resolver = new Laravel\CLI\Tasks\Migrate\Resolver($database);

	return new Cook\Laravel\Migrator($resolver, $database);
});	