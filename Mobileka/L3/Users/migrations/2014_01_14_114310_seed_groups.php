<?php

class Users_Seed_Groups {

  public function __construct()
  {
    Bundle::start('users');
  }

	public function up()
	{
		foreach (Config::get('users::group_seeds', array()) as $seed)
		{
			$model = IoC::resolve('UserGroupModel');

			foreach ($seed as $key => $value)
			{
				$model->{$key} = $value;
			}

			$model->save();
		}
	}

	public function down()
	{
		foreach (Config::get('users::group_seeds', array()) as $seed)
		{
			$model = IoC::resolve('UserGroupModel');

			if ($model = $model::find(Arr::getItem($seed, 'id', 0)))
			{
				$model->delete();
			}
		}
	}
}
