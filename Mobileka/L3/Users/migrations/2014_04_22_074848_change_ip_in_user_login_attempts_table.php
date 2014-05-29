<?php

class Users_Change_Ip_In_User_Login_Attempts_Table {	

	public function up()
	{
		Schema::table('user_login_attempts', function($table) {
			$table->drop_column('ip');
		});

		Schema::table('user_login_attempts', function($table) {
			$table->string('ip');
		});
	}

	public function down()
	{
		Schema::table('user_login_attempts', function($table) {
			$table->drop_column('ip');
		});

		Schema::table('user_login_attempts', function($table) {
			$table->date('ip');
		});
	}
}