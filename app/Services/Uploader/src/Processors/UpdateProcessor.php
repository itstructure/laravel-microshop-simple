<?php

namespace App\Services\Uploader\src\Processors;

use Illuminate\Support\Facades\Storage;

/**
 * Class UpdateProcessor
 * @package App\Services\Uploader\src\Processors
 * @author Andrey Girnik <girnikandrey@gmail.com>
 */
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
            ? pathinfo($this->mediafileModel->getPath())['dirname']
            : $this->getNewProcessDirectory();

        $this->outFileName = $this->getNewOutFileName();

        $this->path = $this->processDirectory . '/' . $this->outFileName;

        $this->previousFiles = array_merge(
            [$this->mediafileModel->getPath()], array_values($this->mediafileModel->getThumbs())
        );
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
        $this->mediafileModel->refresh();
        return true;
    }

    protected function afterProcess(): void
    {
        return;
    }
}
