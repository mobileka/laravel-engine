<?php namespace Mobileka\L3\Engine\Laravel;

use Mobileka\L3\Engine\Laravel\Helpers\Arr,
	Mobileka\L3\Engine\Laravel\Lang;

class Date {

	public $date;

	public static function translate($date, $lang = 'ru', $delimiter = '-', $dayIndex = 0, $monthIndex = 1)
	{
		$result = array();
		$yearIndex = $dayIndex ? 0 : 2;
		$date = explode($delimiter, $date);

		$result[$dayIndex] = Arr::getItem($date, $dayIndex, '01');
		$result[$yearIndex] = Arr::getItem($date, $yearIndex, '1970');

		$month = (int)Arr::getItem($date, $monthIndex, 1);
		$result[$monthIndex] = Lang::line('months.'.($month - 1), array(), $lang)->get();
		ksort($result);

		return implode($delimiter, $result);
	}

	public static function make($date = '')
	{
		$self = new static;
		$date = $date ? : '';
		$self->date = (is_string($date)) ? date_create($date) : $date;
		return $self;
	}

	public function subDays($num)
	{
		$this->date = date_sub($this->date, date_interval_create_from_date_string("$num days"));
		return $this;
	}

	public function addDays($num)
	{
		$this->date = date_add($this->date, date_interval_create_from_date_string("$num days"));
		return $this;
	}

	public function get($format = 'Y-m-d')
	{
		return date_format($this->date, $format);
	}

}