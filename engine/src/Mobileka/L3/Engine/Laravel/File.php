<?php namespace Mobileka\L3\Engine\Laravel;

class File extends \Laravel\File {

	public static function upload($file, $type = null, $directory = null)
	{
		if (!$directory)
		{
			$directory = (!File::is(array('jpeg', 'png', 'gif'), $file['tmp_name'])) ? 'docs' : 'images';
		}

		//получаем расширение файла
		$extension = File::extension($file['name']);

		//формируем его название и путь к нему
		$filename = md5(time() . $file['tmp_name']) . '.' . $extension;
		$path = path('uploads') . $directory;

		/**
		 * Если указана папка, в которую дополнительно необходимо вложить файл,
		 * то запищем ее в путь и попытаемся ее создать
		 */
		$path .= $type ? '/' .$type . '/' : '/';
		umask(0);
		static::mkdir($path);

		//перенесем файл из временной в выбранную для сохранения папку
		move_uploaded_file($file['tmp_name'], $path.$filename);

		//Если тип заливаемого файла - картинка, то сжать ее
		if ($directory == 'images')
		{
			Image::make($path.$filename)->
				save($path.$filename, 75);
		}

		//возвращаем имя файла
		return $filename;
	}
}