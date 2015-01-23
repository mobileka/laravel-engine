<?php namespace Cook;

class Replacer {

	public static function renameFile($replacerObject, Constructor $constructor)
	{
		if (method_exists($replacerObject, 'renameFile')) 
		{
			return $replacerObject->renameFile($constructor);
		}

		return null;
	}

	public static function runCommand($replacerObject, $method, Constructor $constructor)
	{
		if (method_exists($replacerObject, $method)) 
		{
			return $replacerObject->{$method}($constructor);
		}

		throw new CException("Method '$method' not found in ". get_class($replacerObject) . '.');
	}

}