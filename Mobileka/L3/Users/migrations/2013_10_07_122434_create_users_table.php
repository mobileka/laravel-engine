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
			$table->text('contacts');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}