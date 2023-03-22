<?php

namespace App\Services\Uploader\src\Processors;

use Illuminate\Support\Facades\Storage;

class UpdateProcessor extends SaveProcessor
{
    /************************* PROCESS ATTRIBUTES *************************/
    /**
     * @var array
     */
    protected $previousFiles;


    /********************** PROCESS INTERNAL METHODS *********************/
    protected function isFileRequired(): bool
    {
        return false;
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function setProcessParams(): void
    {
        if (is_null($this->file)) {
            return;
        }
        $this->currentDisk = $this->mediafileModel->getDisk();
        $this->processDirectory = $this->file->getMimeType() == $this->mediafileModel->getMimeType()
            ? pathinfo($this->mediafileModel->getUrl())['dirname']
            : $this->getNewProcessDirectory();
        $this->previousFiles = [];//TODO: Need to finish.
    }

    /**
     * @return bool
     * @throws \Exception
     */
    protected function process(): bool
    {
        if (!is_null($this->file)) {

            if (!$this->sendFile()) {
                throw new \Exception('Error upload file.');
            }

            Storage::disk($this->currentDisk)->delete($this->previousFiles);

            $this->setMediafileBaseData();
        }

        $this->setMediafileMetaData();

        if (!$this->mediafileModel->save()) {
            throw new \Exception('Error save file data in database.');
        }
        return true;
    }

    protected function afterProcess(): void
    {
        return;
    }
}
