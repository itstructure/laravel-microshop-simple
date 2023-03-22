<?php

namespace App\Services\Uploader\src\Processors;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UploadProcessor extends SaveProcessor
{
    /********************** PROCESS INTERNAL METHODS *********************/
    protected function isFileRequired(): bool
    {
        return true;
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

        $this->setMediafileBaseData();
        $this->setMediafileMetaData();

        if (!$this->mediafileModel->save()) {
            throw new \Exception('Error save file data in database.');
        }
    }

    protected function afterProcess(): void
    {
        if (!empty($this->data['owner_id']) && !empty($this->data['owner_name']) && !empty($this->data['owner_attribute'])) {
            $this->mediafileModel->addOwner($this->data['owner_id'], $this->data['owner_name'], $this->data['owner_attribute']);
        }
    }
}
