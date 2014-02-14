<?php

class Users_Add_Recovery_Data_To_Users_Table {	

	public function up()
	{
		Schema::table('users', function($table) {
			$table->string('recovery_token');
			$table->string('recovery_password');
			$table->date('recovery_request_date');
		});
	}

	public function down()
	{
		Schema::table('users', function($table) {
			$table->drop_column(array('recovery_token', 'recovery_password', 'recovery_request_date'));
		});
	}
}