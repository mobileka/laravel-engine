<?php

/**
 * Редактирование продукта
 *
 * 1) обновить количество моделей в категориях в случае изменения категории
 */
Event::listen('Model saved: products_admin_default_update', function($product, $oldModel = null)
{
	if ($oldModel->category_id != $product->category_id)
	{
		$numberOfModels = $product->models()->count();
		\Categories\Models\Category::decreaseNumberOfItems($oldModel->category_id, $numberOfModels);
		\Categories\Models\Category::increaseNumberOfItems($product->category_id, $numberOfModels);
	}
});

/**
 * Удаление продукта
 *
 * 1) обновить количество моделей в категориях
 */
Event::listen('Model destroyed: products_admin_default', function($product)
{
	$numberOfModels = $product->models()->count();
	\Categories\Models\Category::decreaseNumberOfItems($product->category_id, $numberOfModels);
});