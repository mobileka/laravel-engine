<?php namespace Cook;

use Exception;

class CException extends Exception {

	public function __construct($message = null, $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);

		$this->message = 'Cook: ' . $this->message;
	}

}