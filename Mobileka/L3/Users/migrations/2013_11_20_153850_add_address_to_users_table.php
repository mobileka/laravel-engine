<?php

class Users_Add_Address_To_Users_Table {

	public function up()
	{
		Schema::table('users', function($table) {
			$table->drop_column('car');
		});
		Schema::table('users', function($table) {
			$table->string('car');
			$table->text('address');
		});
	}

	public function down()
	{
		Schema::table('users', function($table) {
			$table->drop_column(array('address', 'car'));
		});
		Schema::table('users', function($table) {
			$table->text('car');
		});
	}
}