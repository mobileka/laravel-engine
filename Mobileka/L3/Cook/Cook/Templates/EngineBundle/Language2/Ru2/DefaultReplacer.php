<?php namespace Cook\Templates\EngineBundle\Language\Ru;

use Laravel\Lang;

class DefaultReplacer {

	public function renameFile($c)
	{
		return $c->tables;
	}

	public function labels($c)
	{
		foreach ($c->columns() as $column) 
		{
			if ($column->ru)
			{
				$c->result->addLn(Q.$column->name.Q.' => '.Q.$column->ru.Q.',');
			}

			if ($column->relation)
			{
				$c->result->addLn(Q.$column->relation->name.Q.' => array(\'title\' => '.Q.$column->ru.Q.'),');
			}
		}

		return $c->result->get();
	}

}