<?php namespace Cook;

use Laravel\Database as DB;

class Storage {

	protected static $algo = 'md5';

	// get a row from the storage table.
	public function get($file, $result)
	{
		$hash = hash(static::$algo, $result);

		return  DB::table('cook_storage')->where_file_and_hash($file, $hash)->first();
	}

	// Log a file hash in the storage table.
	public function log($file, $result)
	{
		$hash = hash(static::$algo, $result);

		return  DB::table('cook_storage')->insert(compact('file', 'hash'));
	}

	// Delete a row from the storage table.
	public function delete($file, $result)
	{
		$hash = hash(static::$algo, $result);

		return  DB::table('cook_storage')->where_file_and_hash($file, $hash)->delete();
	}

	// Create the database file hashes storage table used by Generator.
	public static function install()
	{
		Laravel\Schema::table('cook_storage', function($table)
		{
			$table->create();

			$table->string('file', 200);

			$table->string('hash', 50);
		});

		echo "Cook: Storage table created successfully.";
	}

}