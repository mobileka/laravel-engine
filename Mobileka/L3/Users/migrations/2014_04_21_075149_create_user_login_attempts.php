<?php

class Users_Create_User_Login_Attempts {	

	public function up()
	{
		Schema::create('user_login_attempts', function($table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->string('username');
			$table->integer('attempts')->unsigned();
			$table->string('ip', 15)->default('x');
			$table->date('last_fail');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('user_login_attempts');
	}
}