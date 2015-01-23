<?php namespace Cook\Templates\EngineBundle\Controllers;

use Laravel\Str;

class DefaultReplacer {

	public function renameFile($c)
	{
		return $c->tables;
	}

	public function with($c)
	{
		if (!$c->relations()) 
		{
			return null;
		}

		$c->result->addLn('$this->with(array(');

		foreach ($c->relations() as $relation) 
		{
			$c->result->addLn(T.Q.$relation->name.Q.',');
		}

		$c->result->addLn('));');

		return $c->result->get();
	}

	public function controllerPrefix($c)
	{
		return Str::title($c->bundleName);
	}

}