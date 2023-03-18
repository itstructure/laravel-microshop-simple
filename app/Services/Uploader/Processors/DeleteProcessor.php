<?php

namespace App\Services\Uploader\Processors;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\{
    MessageBag, Str
};
use Illuminate\Support\Facades\{
    Storage, Validator
};
use App\Services\Uploader\Classes\ThumbConfig;
use App\Services\Uploader\Helpers\{
    ImageHelper, ThumbHelper
};
use App\Services\Uploader\Models\Mediafile;

class DeleteProcessor extends BaseProcessor
{
    /********************** PROCESS INTERNAL METHODS *********************/
    protected function setProcessParams(): void
    {
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
}
