<?php

/**
 * Редактирование модели
 * @tested false
 */
Event::listen('Model saved: models_admin_default_update', function($model, $oldModel)
{
	Event::fire('Model destroyed: models_admin_default', array($oldModel));
	Event::fire('Model saved: models_admin_default_create', array($model));

	/**
	 * Обновить картинки и названия в таблице связанных моделей
	 */
	\RelatedItems\Models\RelatedItem::syncImageAndLabel($model);
});

/**
 * Создание модели
 */
Event::listen('Model saved: models_admin_default_create', function($model, $oldModel = null)
{
	\Categories\Models\Category::increaseNumberOfItems($model->product->category);
});

/**
 * Удаление модели
 */
Event::listen('Model destroyed: models_admin_default', function($model, $oldModel = null)
{
	\Categories\Models\Category::decreaseNumberOfItems($model->product->category);
});