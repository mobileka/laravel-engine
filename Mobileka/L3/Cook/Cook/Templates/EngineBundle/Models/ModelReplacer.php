<?php namespace Cook\Templates\EngineBundle\Models;

class ModelReplacer {

	public function renameFile($c)
	{
		return $c->Table;
	}

	public function accessible($c)
	{
		foreach ($c->columns() as $column) 
		{
			$c->result->addLn(Q.$column->name.Q.',');
		}

		return $c->result->get();
	}

	public function rules($c)
	{
		foreach ($c->columns() as $column) 
		{
			if ( ! in_array($column->name, array('id', 'user_id', 'created_at', 'updated_at')))
			{
				$c->result->addLn(Q.$column->name.Q.' => '.Q.($column->rule ? $column->rule : 'required').Q.',');
			}
		}

		return $c->result->get();
	}

	public function relations($c)
	{
		foreach ($c->relations() as $relation) 
		{
			$c->result->addLn('public function '.$relation->name.'()');

			$c->result->addLn('{');

			$c->result->addLn(T.'return $this->belongs_to(IoC::resolve('.Q.$relation->Name.'Model'.Q.'));');

			$c->result->addLn('}');

			$c->result->addLn();
		}

		return $c->result->get();
	}

}