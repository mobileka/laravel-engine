<?php

use \Models\Models\Model;

function models_word_form($number)
{
	if ($number % 100 >= 10 && $number % 100 <= 20)
	{
		return 'продуктов';
	}
	else
	{
		switch ($number % 10)
		{
			case 1:
				return 'продукт';
				break;

			case 2:
			case 3:
			case 4:
				return 'продукта';
				break;

			default:
				return 'продуктов';
				break;
		}
	}
}

function found_word_form($number)
{
	return ($number % 10 === 1 && ($number % 100 <= 10 || $number % 100 >= 20 ) ? 'Найден' : 'Найдено');
}

function seeAlso($id = 0)
{
	return Model::seeAlso($id);
}

function carouselModels()
{
	return Model::carouselModels();
}