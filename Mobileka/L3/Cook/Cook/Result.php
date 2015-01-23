<?php namespace Cook;

class Result {

	public $result;

	public $tabs;

	public function reset()
	{
		$this->result = null;

		return $this;
	}

	public function add($string)
	{
		$this->result .= $string;

		return $this;
	}

	public function addLn($string = null)
	{
		$this->result .= $this->tabs . $string . PHP_EOL;
		
		return $this;
	}

	public function get()
	{
		$result = $this->result;

		$this->reset();

		return trim($result);
	}

}