<?php

class Users_Seed_Users {

  public function __construct()
  {
    Bundle::start('users');
  }

	public function up()
	{
		foreach (Config::get('users::user_seeds', array()) as $seed)
		{
			$model = IoC::resolve('UserModel');

			foreach ($seed as $key => $value)
			{
				$model->{$key} = $value;
			}

			$model->save();
		}
	}

	public function down()
	{
		foreach (Config::get('users::user_seeds', array()) as $seed)
		{
			$model = IoC::resolve('UserModel');
			$model = $model::where_email($seed['email'])->
				where_password(Hash::make($seed['password']))->
				first();

			if ($model)
			{
				$model->delete();
			}
		}
	}
}
