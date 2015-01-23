<?php namespace Cook\Laravel;

use Cook\CException;
use Cook\Generator;
use Laravel\Database\Schema as LaravelSchema;
use Laravel\IoC;

class Schema extends LaravelSchema {

	public static function execute($table)
	{
		try 
		{
			$constructor = IoC::resolve('Constructor')->setTable($table);

			$template = IoC::resolve('Template')->setConstructor($constructor);

			$generator = IoC::resolve('Generator')->setTemplate($template);
				
			$generator->run();
		} 
		catch (CException $e)
		{
			if ($e->getCode() !== 500)
			{
				throw $e;
			}
		}

		parent::execute($table);
	}

}