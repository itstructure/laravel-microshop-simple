<?php

namespace App\Services\Uploader\Processors;

use Illuminate\Support\Facades\Storage;

class DeleteProcessor extends BaseProcessor
{
    /********************** PROCESS INTERNAL METHODS *********************/
    protected function setProcessParams(): void
    {
        $this->currentDisk = $this->mediafileModel->getDisk();

        $originalPathinfo = pathinfo($this->mediafileModel->getUrl());
        $dirnameParent = substr($originalPathinfo['dirname'], 0, -(SaveProcessor::DIR_LENGTH_SECOND + 1));
        $childDirectories = Storage::disk($this->currentDisk)->directories($dirnameParent);
        $this->processDirectory = count($childDirectories) == 1
            ? $dirnameParent
            : $originalPathinfo['dirname'];
    }

    protected function process(): bool
    {
        Storage::disk($this->currentDisk)->deleteDirectory($this->processDirectory);

        $deleted = $this->mediafileModel->delete();

        if (empty($deleted)) {
            throw new \Exception('Error delete file data from database.');
        }

        return $deleted;
    }

    protected function afterProcess(): void
    {
        return;
    }
}
