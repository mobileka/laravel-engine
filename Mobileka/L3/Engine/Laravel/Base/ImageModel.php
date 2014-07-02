<?php namespace Mobileka\L3\Engine\Laravel\Base;

use Mobileka\L3\Engine\Laravel\Helpers\Arr;
use Mobileka\L3\Engine\Laravel\Helpers\Debug;
use Mobileka\L3\Engine\Laravel\File;
use Mobileka\L3\Engine\Laravel\Image;
use Mobileka\L3\Engine\Laravel\Date;
use Mobileka\L3\Engine\Laravel\Config;
use Mobileka\L3\Engine\Laravel\Input;

use Laravel\IoC;
use Laravel\Database;
use Laravel\Log;

class ImageModel extends Model
{
    public function saveData($data = array(), $safe = array())
    {
        $relations = array();

        list($data, $relationData, $translations) = $this->parseInputData($data);
        list($safe, $safeRelationData, $safeTranslations) = $this->parseInputData($safe, true);

        static::$data = array(
            'data' => $data,
            'safe' => $safe,

            'relationData' => $relationData,
            'safeRelationData' => $safeRelationData,

            'translations' => $translations,
            'safeTranslations' => $safeTranslations
        );

        foreach ($relationData as $relation => $data) {
            $relations[] = $relation;
            $this->{$relation}()->fill($data);
        }

        foreach ($safeRelationData as $relation => $data) {
            $relations[] = $relation;
            $key = key($data);
            $value = value($data);

            $this->{$relation}()->{$key} = $value;
        }

        try {
            Database::connection()->pdo->beginTransaction();

            foreach (array_unique($relations) as $relation) {
                $model = $this->{$relation};

                if (!$model->save()) {
                    $this->mergeRelationErros($model, $relation);
                }
            }

            $polymorphicData = array();

            if (static::$polymorphicRelations) {
                foreach (static::$polymorphicRelations as $relation => $relationParams) {
                    if ($relationData = Arr::getItem($data, $relation)) {
                        $polymorphicData[$relation] = $relationData;
                    }

                    unset($data[$relation]);
                }
            }

            $this->fill($data);

            foreach ($safe as $key => $value) {
                $this->{$key} = $value;
            }

            if ($this->save()) {
                $tokens = Input::get('upload_token');

                foreach (static::$imageFields as $field) {
                    if ($token = Arr::getItem($tokens, $field)) {
                        $uploader = IoC::resolve('Uploader');
                        $img = $uploader::where_token($token)->first();
                    }

                    if (!isset($img) or !$img) {
                        continue;
                    }

                    $type = $this->table() . '/' . Date::make($img->created_at)->get('Y-m');
                    $cropData = Input::get($field, array());

                    if ($cropData) {
                        File::saveCroppedImage($img, $type, 'images', $cropData);
                    }

                    $this->save();
                }

                $this->uploadFiles();
                $this->savePolymorphicData($polymorphicData);

                if (!$this->beforeLocalizedSave()) {
                    throw new \PDOException('beforeLocalizedSave() returned false', 12);
                }

                $this->saveLocalizedData(static::$data['translations'], static::$data['safeTranslations']);

                if (!$this->afterLocalizedSave()) {
                    throw new \PDOException('afterLocalizedSave() returned false', 12);
                }
            }

            if ((bool) $this->errors->messages) {
                throw new \PDOException('There are '. count($this->errors->messages) . ' validation errors detected', 12);
            }

            Database::connection()->pdo->commit();
        } catch (\PDOException $e) {
            if (!in_array($e->getCode(), array('42S22'))) {
                Log::info("\n\n\n###################################################################################################n");
                Debug::log_pp("Exception code: " . $e->getCode() . "\n", false);
                Debug::log_pp($e->getMessage(), false);
                Log::info("\n###################################################################################################n\n\n");

                return false;
            }

            throw $e;
        }

        return true;
    }

    public function getImageSrc($image, $alias = '', $crop = false, $dummy = true)
    {
        $dimensions = Config::find('image.aliases.'.$alias, array());
        $alias = ($alias == 'original' ? '' : $alias);

        if (is_string($image)) {
            if (strpos($this->$image, 'http') === 0) {
                return $this->$image;
            }

            $filename = $this->$image;

            if (!$filename) {
                $relation = $image . '_uploads';

                $images = $this->$relation;

                if ($images) {
                    $filename = $images[0]->filename;
                    $created_at = $images[0]->created_at;
                } else {
                    $created_at = date('Y-m');
                }

                $type = $this->table();
            } elseif (!is_object($filename)) {
                $image = IoC::resolve('Uploader')->find($filename);
                $filename = $image->filename;
                $type = $image->type;
                $created_at = $image->created_at;
            }

        } else {
            $filename = $image->filename;
            $type = $image->type;
            $created_at = $image->created_at;
        }

        $originalName = $crop ? 'crop_' . $filename : $filename;
        $original = imagePath($originalName, $type, $created_at);

        if (!is_file($original)) {
            return $dummy ? dummyThumbnail($alias) : null;
        }

        $name = ($crop ? 'crop_' : '') . ($alias ? $alias . '_' : '') . $filename;
        $thumbnail = imagePath($name, $type, $created_at);
        $thumbUrl = image($name, $type, $created_at);

        if (!is_file($thumbnail) and is_file($original)) {
            Image::make($original)->
                resize($dimensions[0], $dimensions[1], Arr::getItem($dimensions, 2, true), false)->
                save($thumbnail);
        }

        return $thumbUrl;
    }
}
