<?php

Event::listen('bind-uploads', function($id, $tokens)
{
	if (!is_array($tokens))
	{
		$tokens = array($tokens);
	}

	if ($id and $tokens)
	{
		foreach ($tokens as $fieldName => $token)
		{
			$model = IoC::resolve('Uploader');
			$model->where_token($token)->
				update(array('object_id' => (int)$id));
		}

		return true;
	}

	return false;
});
