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
        return [
            'alt' => 'nullable|string|max:128',
            'title' => 'nullable|string|max:128',
            'description' => 'nullable|string|max:2048',
        ];
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

            $this->setBaseMediafileData();
        }

        $this->setTextMediafileData();

        if (!$this->mediafileModel->save()) {
            throw new \Exception('Error save file data in database.');
        }
    }

    protected function afterProcess(): void
    {
        return;
    }
}
