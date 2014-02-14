<?php

class Users_Create_User_Groups_Table {	

	public function up()
	{
		Schema::create('user_groups', function($table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->string('code');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('user_groups');
	}
}