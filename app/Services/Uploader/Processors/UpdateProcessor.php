<?php

namespace App\Services\Uploader\Processors;

use Illuminate\Support\Facades\Storage;

class UpdateProcessor extends SaveProcessor
{
    /************************* PROCESS ATTRIBUTES *************************/
    /**
     * @var array
     */
    protected $previousFiles;


    /********************** PROCESS INTERNAL METHODS *********************/
    protected function getValidateRules(): array
    {
        return [];
    }

    protected function setProcessParams(): void
    {
        if (is_null($this->file)) {
            return;
        }
        $this->currentDisk = $this->mediafileModel->getDisk();
        $this->processDirectory = $this->file->getMimeType() == $this->mediafileModel->getType()
            ? pathinfo($this->mediafileModel->getUrl())['dirname']
            : $this->getNewProcessDirectory();
        $this->previousFiles = [];//TODO: Need to finish.
    }

    protected function process(): bool
    {
        if (!is_null($this->file)) {

            if (!$this->sendFile()) {
                throw new \Exception('Error upload file.');
            }

            Storage::disk($this->currentDisk)->delete($this->previousFiles);

            $this->mediafileModel->url = $this->databaseUrl;
            $this->mediafileModel->filename = $this->outFileName;
            $this->mediafileModel->size = $this->file->getSize();
            $this->mediafileModel->type = $this->file->getMimeType();
            $this->mediafileModel->disk = Storage::getDefaultDriver();
        }

        $this->mediafileModel->alt = $this->data['alt'];
        $this->mediafileModel->title = $this->data['title'];
        $this->mediafileModel->description = $this->data['description'];

        if (!$this->mediafileModel->save()) {
            throw new \Exception('Error save file data in database.');
        }
    }

    protected function afterProcess(): void
    {
        return;
    }
}
