<?php namespace Cook\Laravel;

use Laravel\CLI\Tasks\Migrate\Migrator as LaravelMigrator;
use Laravel\IoC;
use Cook\Storage;

class Migrator extends LaravelMigrator {

	// Create the database file hashes storage table used by Generator.
	public function install_cook()
	{
		Storage::install();
	}

	/**
	 * Run the outstanding migrations for a given bundle.
	 *
	 * @param  string  $bundle
	 * @param  int     $version
	 * @return void
	 */
	public function migrate($bundle = null, $version = null)
	{
		$migrations = $this->resolver->outstanding($bundle);

		if (count($migrations) == 0)
		{
			echo "No outstanding migrations.";

			return;
		}

		// We need to grab the latest batch ID and increment it by one.
		// This allows us to group the migrations so we can easily
		// determine which migrations need to roll back.
		$batch = $this->database->batch() + 1;

		foreach ($migrations as $migration)
		{
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			
			// Add bundle where migration is declared, to current Constructor 
			IoC::resolve('Constructor')->setMigration($migration);

			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

			$migration['migration']->up();

			echo 'Migrated: '.$this->display($migration).PHP_EOL;

			// After running a migration, we log its execution in the migration
			// table so that we can easily determine which migrations we'll
			// reverse in the event of a migration rollback.
			$this->database->log($migration['bundle'], $migration['name'], $batch);
		}
	}

	/**
	 * Rollback the latest migration command.
	 *
	 * @param  array  $arguments
	 * @return bool
	 */
	public function rollback($arguments = array())
	{
		$migrations = $this->resolver->last();

		// If bundles supplied, filter migrations to rollback only bundles'
		// migrations.
		if (count($arguments) > 0)
		{
			$bundles = $arguments;
			
			if ( ! is_array($bundles)) $bundles = array($bundles);
			
			$migrations = array_filter($migrations, function($migration) use ($bundles)
			{
				return in_array($migration['bundle'], $bundles);
			});
		}

		if (count($migrations) == 0)
		{
			echo "Nothing to rollback.".PHP_EOL;

			return false;
		}

		// The "last" method on the resolver returns an array of migrations,
		// along with their bundles and names. We will iterate through each
		// migration and run the "down" method.
		foreach (array_reverse($migrations) as $migration)
		{
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

			// Add bundle where migration is declared, to current Constructor 
			IoC::resolve('Constructor')->setMigration($migration);

			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

			$migration['migration']->down();

			echo 'Rolled back: '.$this->display($migration).PHP_EOL;

			// By only removing the migration after it has successfully rolled back,
			// we can re-run the rollback command in the event of any errors with
			// the migration and pick up where we left off.
			$this->database->delete($migration['bundle'], $migration['name']);
		}

		return true;
	}
 
}