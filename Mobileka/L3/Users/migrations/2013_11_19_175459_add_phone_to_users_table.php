<?php

class Users_Add_Phone_To_Users_Table {	

	public function up()
	{
		Schema::table('users', function($table) {
			$table->text('phone');
		});
	}

	public function down()
	{
		Schema::table('users', function($table) {
			$table->drop_column('phone');
		});
	}
}