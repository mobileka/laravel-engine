<?php

class Users_Drop_Users_Group_Id_Foreign_Key {

	public function up()
	{
		try
		{
			Schema::table('users', function($table) {
				$table->drop_foreign('users_group_id_foreign');
			});
		}
		catch(Exception $e)
		{}
	}

	public function down()
	{
		try
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
		catch(Exception $e)
		{}
	}
}