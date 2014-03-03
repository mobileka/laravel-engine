<?php

class Users_Create_Users_Table {

	public function up()
	{
		Schema::create('users', function($table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->string('email');
			$table->string('password');
			$table->integer('group_id')->unsigned()->index();
			$table->string('name');

			$table->string('recovery_token');
			$table->string('recovery_password');
			$table->date('recovery_request_date');

			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}