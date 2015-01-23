<?php namespace Cook\Templates\EngineBundle;

use DirectoryIterator;
use Laravel\Bundle;

class CrudReplacer {

	public function lists($c)
	{
		foreach ($c->relations() as $relation) 
		{
			$c->result->addLn(Q.$relation->name.Q.' => IoC::resolve('.Q.$relation->Name.'Model'.Q.')->lists('.Q.'title'.Q.', '.Q.'id'.Q.'),');
		}

		return $c->result->get();
	}

	public function formComponents($c)
	{
		foreach (new DirectoryIterator(Bundle::path('engine').'Form'.DS.'Components') as $file) 
		{
			if ($file->isFile())
			{
				$c->result->addLn('use Mobileka\L3\Engine\Form\Components\\'.$file->getBasename('.php').' as '.$file->getBasename('.php').'Form;');
			}
		}

		return $c->result->get();
	}

	public function gridComponents($c)
	{
		foreach (new DirectoryIterator(Bundle::path('engine').'Grid'.DS.'Components') as $file) 
		{
			if ($file->isFile())
			{
				$c->result->addLn('use Mobileka\L3\Engine\Grid\Components\\'.$file->getBasename('.php').' as '.$file->getBasename('.php').'Grid;');
			}
		}

		return $c->result->get();
	}

	public function filterComponents($c)
	{
		foreach (new DirectoryIterator(Bundle::path('engine').'Grid'.DS.'Filters') as $file) 
		{
			if ($file->isFile())
			{
				$c->result->addLn('use Mobileka\L3\Engine\Grid\Filters\\'.$file->getBasename('.php').' as '.$file->getBasename('.php').'Filter;');
			}
		}

		return $c->result->get();
	}

	public function configForm($c)
	{
		foreach ($c->columns() as $column) 
		{
			if ( ! in_array($column->name, array('id', 'user_id', 'created_at', 'updated_at')))
			{
				if ($column->type === 'text')
				{
					$c->result->addLn(Q.$column->name.Q.' => TextAreaForm::make('.Q.$column->name.Q.'),');
					continue;
				}

				if ($column->type === 'boolean')
				{
					$c->result->addLn(Q.$column->name.Q.' => CheckboxForm::make('.Q.$column->name.Q.'),');
					continue;
				}

				if ($column->type === 'integer' and substr($column->name, -3) === '_id')
				{
					$c->result->addLn(Q.$column->name.Q.' => DropdownChosenForm::make('.Q.$column->name.Q.')->options($lists['.Q.substr($column->name, 0, -3).Q.']),');
					continue;
				}
				
				$c->result->addLn(Q.$column->name.Q.' => TextForm::make('.Q.$column->name.Q.'),');
			}
		}

		return $c->result->get();
	}

	public function configGrid($c)
	{
		foreach ($c->columns() as $column) 
		{
			if ( ! in_array($column->name, array('updated_at')))
			{
				if ($column->type === 'integer' and substr($column->name, -3) === '_id')
				{
					$c->result->addLn(Q.substr($column->name, 0, -3).'.title'.Q.' => ColumnGrid::make('.Q.substr($column->name, 0, -3).'.title'.Q.'),');
					continue;
				}

				if ($column->type === 'boolean')
				{
					$c->result->addLn(Q.$column->name.Q.' => SwitcherGrid::make('.Q.$column->name.Q.'),');
					continue;
				}

				$c->result->addLn(Q.$column->name.Q.' => ColumnGrid::make('.Q.$column->name.Q.'),');
			}
		}

		return $c->result->get();
	}

	public function configFilter($c)
	{
		foreach ($c->columns() as $column) 
		{
			if ( ! in_array($column->name, array('updated_at')))
			{
				if ($column->type === 'integer' and substr($column->name, -3) === '_id')
				{
					$c->result->addLn(Q.$column->name.Q.' => DropdownChosenFilter::make('.Q.$column->name.Q.')->options($lists['.Q.substr($column->name, 0, -3).Q.']),');
					continue;
				}

				if ($column->name === 'id')
				{
					$c->result->addLn(Q.$column->name.Q.' => TextFilter::make('.Q.$column->name.Q.'),');
					continue;
				}

				if ($column->name === 'created_at')
				{
					$c->result->addLn(Q.$column->name.Q.' => DateRangeFilter::make('.Q.$column->name.Q.'),');
					continue;
				}

				$c->result->addLn(Q.$column->name.Q.' => ContainsFilter::make('.Q.$column->name.Q.'),');
			}
		}

		return $c->result->get();
	}

}