<?php

/**
 * Редактирование категории
 * 1) обновить количество моделей в категориях в случае изменения родителя категории
 */
Event::listen('Model saved: categories_admin_default_update', function($category, $oldModel = null)
{
	/**
	 * При изменения родиельской категории, необходимо пересчитать количество
	 * моделей, которые лежат в категориях
	 */
	if ($oldModel->parent_id != $category->parent_id)
	{
		$products = $category->products;

		$numberOfModels = 0;

		foreach ($products as $product)
		{
			$numberOfModels += $product->models()->count();
		}

		\Categories\Models\Category::decreaseNumberOfItems($oldModel->parent_id, $numberOfModels);
		\Categories\Models\Category::increaseNumberOfItems($category->id, $numberOfModels);
	}

	/**
	 * Обновить картинки и названия в таблице связанных моделей
	 */
	\RelatedItems\Models\RelatedItem::syncImageAndLabel($category, 'categories');
});