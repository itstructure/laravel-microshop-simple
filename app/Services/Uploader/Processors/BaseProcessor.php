<?php

namespace App\Services\Uploader\Processors;

use \Exception;
use Illuminate\Http\UploadedFile;
use App\Services\Uploader\Models\Mediafile;
use App\Services\Uploader\Interfaces\UploadProcessorInterface;

abstract class BaseProcessor implements UploadProcessorInterface
{
    public static function getInstance(array $config)
    {
        $obj = new static();
        foreach ($config as $key => $value) {
            $obj->{'set' . ucfirst($key)}($value);
        }
        return $obj;
    }

    /**
     * Set mediafile model.
     * @param Mediafile $model
     */
    public function setMediafileModel(Mediafile $model): void
    {

    }

    /**
     * Get mediafile model.
     * @return Mediafile
     */
    public function getMediafileModel(): Mediafile
    {

    }

    /**
     * Set file.
     * @param UploadedFile|null $file
     * @return void
     */
    public function setFile(UploadedFile $file = null): void
    {

    }

    /**
     * Get file.
     * @return UploadedFile|null
     */
    public function getFile()
    {

    }

    /**
     * Save file in storage and database.
     * @return bool
     */
    public function save(): bool
    {

    }

    /**
     * Delete file from storage and database.
     * @return int
     */
    public function delete(): int
    {

    }

    /**
     * Returns current model id.
     * @return int|string
     */
    public function getId()
    {

    }

    /**
     * Create thumbs for this image
     * @throws Exception
     * @return bool
     */
    public function createThumbs(): bool
    {

    }

    /**
     * Set attributes with their values.
     * @param $values
     * @return mixed
     */
    public function setAttributes($values)
    {

    }

    /**
     * Validate data.
     * @return mixed
     */
    public function validate()
    {

    }

    /**
     * Returns the errors for all attributes.
     * @return array.
     */
    public function getErrors()
    {

    }
}