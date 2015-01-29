<?php namespace Cook;

use Laravel\Database\Schema\Table;
use Laravel\Bundle;
use Laravel\Str;
use Laravel\IoC;
use Laravel\Fluent;

use ReflectionClass;

class Constructor extends Fluent {

	// Result string container
	public $result;

	// Laravel migration commands 
	protected $commands;

	// Like Fluent attributes container
	protected $columns;

	// Relation container
	protected $relations;

	public $bundleName;

	// Adds multiform bundle name
	public function setMigration($migration)
	{
		$this->bundleName = $migration['bundle'];

		$this->addMultiformAttributes('bundle', $this->bundleName);

		return $this;
	}

	// Adds multiform table AND (WTF) relations names
	public function setTable(Table $table)
	{
		$this->addMultiformAttributes('table', $table->name);

		$this->columns = $table->columns;
		$this->commands = $table->commands;

		$this->addMultiformAttributes('command', $table->commands[0]->type);

		$this->addMultiformRelations('name');

		$this->result = new Result();

		return $this;
	}

	public function columns()
	{
		return $this->columns;
	}

	public function relations()
	{
		return $this->relations;
	}

	public function commands()
	{
		return $this->commands;
	}

	public function findReplacers(Template $template)
	{
		$template = $this->findReplacersInAttributes($template);

		$template = $this->findReplacersInReplacerObjects($template);

		$this->checkReplacers($template);

		return $template;
	}

	protected function findReplacersInAttributes(Template $template)
	{
		foreach ($template->tokens as $i => $token) 
		{
			$nakedToken = substr($token, 1, -1);

			if (!isset($template->replacers[$i]) and isset($this->attributes[$nakedToken]))
			{
				$tabs = str_repeat(T, $template->tabs[$i]);

				$template->replacers[$i] = $tabs . $this->$nakedToken;
			}
		}

		return $template;
	}

	// after findReplacersInAttributes()
	protected function findReplacersInReplacerObjects(Template $template)
	{
		foreach ($template->tokens as $i => $token) 
		{
			$nakedToken = substr($token, 1, -1);

			if (!array_key_exists($i, $template->replacers) and is_object($template->replacerObject))
			{
				$this->result->tabs = str_repeat(T, $template->tabs[$i]);

				$template->newName = Replacer::renameFile($template->replacerObject, $this);

				$template->replacers[$i] = Replacer::runCommand($template->replacerObject, $nakedToken, $this);
			}
		}

		return $template;
	}

	// after findReplacersInReplacerObjects()
	protected function checkReplacers(Template $template)
	{
		foreach ($template->tokens as $i => $token) 
		{
			if (!array_key_exists($i, $template->replacers))
			{
				throw new CException("Replacer '$token' not found for template '$template->name' in '$template->root'.");
			}
		}

		return $template;
	}

	protected function addMultiformAttributes($token, $value)
	{
		// Remove _id from column name
		$token = $this->removeId($token);
		$value = $this->removeId($value);

		// Set token to singular and lower
		$defaultToken = Str::lower(Str::singular($token));
		$defaultValue = Str::lower(Str::singular($value));

		// Add all forms tokens
		$this->attributes[$defaultToken.'Name'] = $value;

		$this->attributes[$defaultToken] = $defaultValue;
		$this->attributes[Str::title($defaultToken)] = Str::title($defaultValue);
		$this->attributes[Str::plural($defaultToken)] = Str::plural($defaultValue);
		$this->attributes[Str::title(Str::plural($defaultToken))] = Str::title(Str::plural($defaultValue));
	}

	protected function addMultiformRelations($token)
	{
		$this->relations = array();

		foreach ($this->columns as $column) 
		{
			if ($this->checkId($column->name))
			{
				$relation = new Fluent;

				$relation->attributes = $this->getMultifomArray($token, $column->name);

				$this->relations[] = $relation;

				$column->relation($relation);
			}
		}
	}

	protected function getMultifomArray($token, $value)
	{
		// Remove _id from column name
		$token = $this->removeId($token);
		$value = $this->removeId($value);

		// Set token to singular and lower
		$defaultToken = Str::lower(Str::singular($token));
		$defaultValue = Str::lower(Str::singular($value));

		// Add all forms tokens to result
		$result = array();
		$result[$defaultToken] = $defaultValue;
		$result[Str::title($defaultToken)] = Str::title($defaultValue);
		$result[Str::plural($defaultToken)] = Str::plural($defaultValue);
		$result[Str::title(Str::plural($defaultToken))] = Str::title(Str::plural($defaultValue));

		return $result;
	}

	protected function removeId($columnName)
	{
		if (substr($columnName, -3) === '_id')
		{
			return substr($columnName, 0, -3);
		}

		return $columnName;
	}

	protected function checkId($columnName)
	{
		if (substr($columnName, -3) === '_id')
		{
			return true;
		}

		return false;
	}

}