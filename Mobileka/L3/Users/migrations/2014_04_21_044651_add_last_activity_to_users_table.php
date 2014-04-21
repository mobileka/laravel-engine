<?php

class Users_Add_Last_Activity_To_Users_Table {	

	public function up()
	{
		Schema::table('users', function($table) {
			$table->date('last_activity_date');
		});
	}

	public function down()
	{
		Schema::table('users', function($table) {
			$table->drop_column('last_activity_date');
		});
	}
}