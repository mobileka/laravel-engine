<?php namespace Mobileka\L3\Engine\Grid\Components;

use Mobileka\L3\Engine\Laravel\Helpers\Arr,
	Mobileka\L3\Engine\Laravel\Lang;

class Date extends BaseComponent {

	protected $template = 'engine::grid.column';

	protected $inputFormat = 'Y-m-d H:i:s';

	protected $format = array(
		'lang' => 'ru',
		'delimiter' => ' ',
		'dayIndex' => 0,
		'monthIndex' => 1,
	);

	public function value($lang = '')
	{
		$value = $this->row;
		$tokens = explode('.', $this->name);

		foreach ($tokens as $token)
		{
			$value = $value->{$token};
		}

		if (!$value or $value == '0000-00-00 00:00:00')
		{
			return Lang::findLine($this->languageFile, 'not_specified');
		}

		if (is_array($this->format))
		{
			//ыы
			$format = array();

			$lang = Arr::getItem($this->format, 'lang', 'ru');
			$delimiter = Arr::getItem($this->format, 'delimiter', '.');
			$dayIndex = Arr::getItem($this->format, 'dayIndex', '01');
			$monthIndex = Arr::getItem($this->format, 'monthIndex', '01');
			$yearIndex = $dayIndex ? 0 : 2;

			$format[$dayIndex] = '%d';
			$format[$monthIndex] = '%m';
			$format[$yearIndex] = '%Y';

			$format = implode($delimiter, $format);

			if (strlen($value) === 10) $value .= ' 00:00:00';

			$value = \Carbon::createFromFormat($this->inputFormat, $value)->formatLocalized($format);
			$value = \Date::translate($value, $lang, $delimiter, $dayIndex, $monthIndex);
		}
		else
		{
			$value = \Carbon::createFromFormat($this->inputFormat, $value)->formatLocalized($this->format);
		}

		return ($this->translate) ? Lang::findLine($this->languageFile, $value) : $value;
	}

}
