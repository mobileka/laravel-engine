<?php

class Engine_Create_Uploads_Table {

	public function up()
	{
		Schema::create('uploads', function($table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->string('filename');
			$table->integer('object_id')->unsigned()->index();
			$table->string('type')->index();
			$table->string('token');
			$table->string('fieldname');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('uploads');
	}
}
