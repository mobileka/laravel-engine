<?php

class Engine_Create_I18n_Table {

	public function up()
	{
		Schema::create('i18n', function($table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->string('table');
			$table->integer('object_id')->unsigned()->index();
			$table->string('field');
			$table->string('value');
			$table->string('lang');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('i18n');
	}
}
