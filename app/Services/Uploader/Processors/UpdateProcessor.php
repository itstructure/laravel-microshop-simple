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

class UpdateProcessor extends SaveProcessor
{
    /**
     * @var array
     */
    protected $filesForDelete;

    /********************** PROCESS INTERNAL METHODS *********************/
    protected function setProcessParams(): void
    {

    }

    protected function process(): bool
    {

    }

    /**
     * @return bool
     */
    private function deletePreviousFiles(): bool
    {
        return Storage::disk($this->currentDisk)->delete($this->filesForDelete);
    }
}
