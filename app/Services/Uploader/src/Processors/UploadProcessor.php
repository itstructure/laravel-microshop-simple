<?php

namespace App\Services\Uploader\src\Processors;

use Exception;
use Illuminate\Support\Facades\Storage;

/**
 * Class UploadProcessor
 * @package App\Services\Uploader\src\Processors
 * @author Andrey Girnik <girnikandrey@gmail.com>
 */
class UploadProcessor extends SaveProcessor
{
    /********************** PROCESS INTERNAL METHODS *********************/
    protected function isFileRequired(): bool
    {
        return true;
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function setProcessParams(): void
    {
        $this->currentDisk = Storage::getDefaultDriver();

        $this->processDirectory = $this->getNewProcessDirectory();

        $this->outFileName = $this->getNewOutFileName();

        $this->path = $this->processDirectory . '/' . $this->outFileName;
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
        $this->mediafileModel->refresh();
        return true;
    }

    protected function afterProcess(): void
    {
        if (!empty($this->data['owner_id']) && !empty($this->data['owner_name']) && !empty($this->data['owner_attribute'])) {
            $this->mediafileModel->addOwner($this->data['owner_id'], $this->data['owner_name'], $this->data['owner_attribute']);
        }
    }
}
