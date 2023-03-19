<?php

namespace App\Services\Uploader\Processors;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UploadProcessor extends SaveProcessor
{
    /********************** PROCESS INTERNAL METHODS *********************/
    protected function getValidateRules(): array
    {
        return [];
    }

    protected function setProcessParams(): void
    {
        $this->currentDisk = Storage::getDefaultDriver();

        $this->processDirectory = $this->getNewProcessDirectory();

        $this->outFileName = $this->renameFiles ?
            Str::uuid() . '.' . $this->file->getExtension() :
            Str::slug($this->file->getBasename()) . '.' . $this->file->getExtension();

        $this->databaseUrl = $this->processDirectory . DIRECTORY_SEPARATOR . $this->outFileName;
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function process(): bool
    {
        if (!$this->sendFile()) {
            throw new \Exception('Error upload file.');
        }

        $this->mediafileModel->url = $this->databaseUrl;
        $this->mediafileModel->filename = $this->outFileName;
        $this->mediafileModel->size = $this->file->getSize();
        $this->mediafileModel->type = $this->file->getMimeType();
        $this->mediafileModel->disk = $this->currentDisk;

        $this->mediafileModel->alt = $this->data['alt'];
        $this->mediafileModel->title = $this->data['title'];
        $this->mediafileModel->description = $this->data['description'];

        if (!$this->mediafileModel->save()) {
            throw new \Exception('Error save file data in database.');
        }
    }

    protected function afterProcess(): void
    {
        if (!empty($this->data['owner_id']) && !empty($this->data['owner']) && !empty($this->data['owner_attribute'])) {
            $this->mediafileModel->addOwner($this->data['owner_id'], $this->data['owner'], $this->data['owner_attribute']);
        }
    }
}
