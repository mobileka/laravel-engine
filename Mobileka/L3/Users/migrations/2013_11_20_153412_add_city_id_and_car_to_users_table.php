<?php

class Users_Add_City_Id_And_Car_To_Users_Table {

	public function up()
	{
		Schema::table('users', function($table) {
			$table->integer('city_id')->unsigned()->index();
			$table->text('car');
		});
	}

	public function down()
	{
		Schema::table('users', function($table) {
			$table->drop_column(array('city_id', 'car'));
		});
	}
}