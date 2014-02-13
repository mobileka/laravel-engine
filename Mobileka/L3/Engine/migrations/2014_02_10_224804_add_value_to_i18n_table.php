<?php

class Engine_Add_Value_To_I18n_Table {

	public function up()
	{
		Schema::table('i18n', function($table) {
			$table->drop_column('value');
		});

		Schema::table('i18n', function($table) {
			$table->text('value');
		});
	}

	public function down()
	{
		Schema::table('i18n', function($table) {
			$table->drop_column('value');
		});

		Schema::table('i18n', function($table) {
			$table->string('value');
		});
	}
}
