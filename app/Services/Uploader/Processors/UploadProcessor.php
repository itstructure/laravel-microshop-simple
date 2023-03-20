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
        return [
            'alt' => 'nullable|string|max:128',
            'title' => 'nullable|string|max:128',
            'description' => 'nullable|string|max:2048',
            'owner_id' => 'nullable|numeric',
            'owner_name' => 'nullable|string|max:64',
            'owner_attribute' => 'nullable|string|max:64',
        ];
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

        $this->setBaseMediafileData();
        $this->setTextMediafileData();

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
