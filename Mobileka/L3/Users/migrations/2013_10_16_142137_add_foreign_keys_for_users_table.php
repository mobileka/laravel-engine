<?php

class Users_Add_Foreign_Keys_For_Users_Table {

	public function up()
	{
		Schema::table('users', function($table)
		{
			$table->foreign('group_id')->
				references('id')->
				on('user_groups')->
				on_update('cascade')->
				on_delete('restrict');
		});
	}

	public function down()
	{
		Schema::table('users', function($table) {
			$table->drop_foreign('users_group_id_foreign');
		});
	}
}