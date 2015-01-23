<?php namespace <Bundles>\Models;

use Laravel\IoC;
use Base\Models\Model as BaseModel;

class <Table> extends BaseModel {

	public static $table = '<tables>';

	public static $accessible = array(
		<accessible>
	);

	public static $rules = array(
		<rules>
	);

	<relations>

}