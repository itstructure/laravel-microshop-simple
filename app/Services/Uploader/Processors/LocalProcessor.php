<?php

namespace App\Services\Uploader\Processors;

use Illuminate\Support\Facades\Storage;
use App\Services\Uploader\Interfaces\ThumbConfigInterface;
use App\Services\Uploader\UploaderService;
use App\Helpers\ImageHelper;

class LocalProcessor extends BaseProcessor
{
    /************************* PROCESS ATTRIBUTES ************************/
    /**
     * @var string
     */
    private $directoryForDelete;


    /************************* PROCESS METHODS ***************************/

    /**
     * Set some params for delete.
     * It is needed to set the next parameters:
     * $this->directoryForDelete
     * @return void
     */
    protected function setParamsForDelete(): void
    {
        $originalPathinfo = pathinfo($this->mediafileModel->getUrl());

        $dirnameParent = substr($originalPathinfo['dirname'], 0, -(self::DIR_LENGTH_SECOND + 1));
        $childDirectories = Storage::disk($this->mediafileModel->getDisk())->directories($dirnameParent);

        $this->directoryForDelete = count($childDirectories) == 1
            ? $dirnameParent
            : $originalPathinfo['dirname'];
    }

    /**
     * Save file in local directory.
     * @return bool
     */
    protected function sendFile(): bool
    {
        Storage::putFileAs($this->uploadDirectory, $this->file, $this->outFileName);

        return Storage::fileExists($this->uploadDirectory . DIRECTORY_SEPARATOR . $this->outFileName);
    }

    /**
     * Delete local directory with original file and thumbs.
     * @return bool
     */
    protected function deleteFiles(): bool
    {
        return Storage::deleteDirectory($this->directoryForDelete);
    }

    /**
     * Create thumb.
     * @param ThumbConfigInterface $thumbConfig
     * @return string
     */
    protected function createThumb(ThumbConfigInterface $thumbConfig)
    {
        $originalPathInfo = pathinfo($this->mediafileModel->url);

        $thumbPath = $originalPathInfo['dirname'] .
            DIRECTORY_SEPARATOR .
            $this->getThumbFilename($originalPathInfo['filename'],
                $originalPathInfo['extension'],
                $thumbConfig->getAlias(),
                $thumbConfig->getWidth(),
                $thumbConfig->getHeight()
            );

        $thumbContent = ImageHelper::thumbnail(Storage::path($this->mediafileModel->url),
            $thumbConfig->getWidth(),
            $thumbConfig->getHeight(),
            $thumbConfig->getMode()
        )->get($originalPathInfo['extension']);

        Storage::put($thumbPath, $thumbContent);

        return $thumbPath;
    }

    /**
     * Actions after main save.
     * @return mixed
     */
    protected function afterSave()
    {
        if (!empty($this->data['owner_id']) && !empty($this->data['owner']) && !empty($this->data['owner_attribute'])) {
            $this->mediafileModel->addOwner($this->data['owner_id'], $this->data['owner'], $this->data['owner_attribute']);
        }
    }
}