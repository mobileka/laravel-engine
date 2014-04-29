<?php namespace Mobileka\L3\Engine;

use \Lang, \DB;

class i18n {

	protected $i18n;
	public static $lang;

	public function __construct(\Laravel\Database\Eloquent\Model $model)
	{
		$this->i18n = $model;
	}

	public function getTranslationsByModel($model, $lang = '')
	{
		$translation = $this->i18n->where_table($model->table())->
			where_object_id($model->id);

		if ($lang)
		{
			$translation = $translation->where_lang($lang);
		}

		return $translation->get();
	}

	public function getByModel($field, $model, $lang = '', $defaultValue = '')
	{
		$lang = $lang ?: static::getCurrentLang();
		$field = str_replace('_'.$lang, '', $field);

		$translation = $this->i18n->where_table($model->table())->
			where_field($field)->
			where_object_id($model->id)->
			where_lang($lang)->
			first();

		return !$translation ? $defaultValue : $translation->value;
	}

	public function saveByModel($field, $value, $model, $lang = '')
	{
		$lang = $lang ?: static::getCurrentLang();

		$this->i18n->where_table($model->table())->
			where_field($field)->
			where_object_id($model->id)->
			where_lang($lang)->
			delete();

		$i18n = new $this->i18n;
		$i18n->table = $model->table();
		$i18n->field = $field;
		$i18n->object_id = $model->id;
		$i18n->lang = $lang;
		$i18n->value = $value;

		return $i18n->save();
	}

	public static function setCurrentLang($lang)
	{
		/**
		 * @todo Implement this
		 */

		\Helpers\Debug::pp('i18n::setCurrentLang() not implemented yet');
	}

	public static function getCurrentLang()
	{
		return \Config::get('application.language');
	}

}

