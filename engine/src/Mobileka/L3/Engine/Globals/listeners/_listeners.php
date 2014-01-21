<?php

Event::listen('bind-uploads', function($id, $token)
{
	if ($id and $token)
	{
		$model = new \Uploads\Models\Upload;
		$model->where_token($token)
			->update(array('object_id' => (int)$id));

		return true;
	}

	return false;
});