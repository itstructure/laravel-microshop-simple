<?php

namespace App\Services\Uploader\Interfaces;

use \Exception;
use Illuminate\Http\UploadedFile;
use App\Services\Uploader\Models\Mediafile;

/**
 * Interface UploadProcessorInterface
 * @author Andrey Girnik <girnikandrey@gmail.com>
 */
interface UploadProcessorInterface
{
    const FILE_TYPE_IMAGE = 'image';
    const FILE_TYPE_AUDIO = 'audio';
    const FILE_TYPE_VIDEO = 'video';
    const FILE_TYPE_APP = 'application';
    const FILE_TYPE_APP_WORD = 'word';
    const FILE_TYPE_APP_EXCEL = 'excel';
    const FILE_TYPE_APP_PDF = 'pdf';
    const FILE_TYPE_TEXT = 'text';
    const FILE_TYPE_OTHER = 'other';
    const FILE_TYPE_THUMB = 'thumbnail';

    /**
     * Set mediafile model.
     * @param Mediafile $model
     */
    public function setMediafileModel(Mediafile $model): void;

    /**
     * Get mediafile model.
     * @return Mediafile
     */
    public function getMediafileModel(): Mediafile;

    /**
     * Set file.
     * @param UploadedFile|null $file
     * @return void
     */
    public function setFile(UploadedFile $file = null): void;

    /**
     * Get file.
     * @return UploadedFile|null
     */
    public function getFile();

    /**
     * Save file in storage and database.
     * @return bool
     */
    public function save(): bool;

    /**
     * Delete file from storage and database.
     * @return int
     */
    public function delete(): int;

    /**
     * Returns current model id.
     * @return int|string
     */
    public function getId();

    /**
     * Create thumbs for this image
     * @throws Exception
     * @return bool
     */
    public function createThumbs(): bool;

    /**
     * Set attributes with their values.
     * @param $values
     * @return mixed
     */
    public function setAttributes($values);

    /**
     * Validate data.
     * @return mixed
     */
    public function validate();

    /**
     * Returns the errors for all attributes.
     * @return array.
     */
    public function getErrors();
}
