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

class UploadProcessor extends SaveProcessor
{
    /************************* CONFIG ATTRIBUTES *************************/
    


    /********************** PROCESS INTERNAL METHODS *********************/
    protected function setProcessParams(): void
    {
        $processDirectory = rtrim(rtrim($this->getUploadDirConfig($this->file->getMimeType()), '/'), '\\');

        if (!empty($this->data['subDir'])) {
            $processDirectory = $processDirectory . DIRECTORY_SEPARATOR . trim(trim($this->data['subDir'], '/'), '\\');
        }

        $this->processDirectory = $processDirectory .
            DIRECTORY_SEPARATOR . substr(md5(time()), 0, self::DIR_LENGTH_FIRST) .
            DIRECTORY_SEPARATOR . substr(md5(microtime() . $this->file->getBasename()), 0, self::DIR_LENGTH_SECOND);

        $this->outFileName = $this->renameFiles ?
            Str::uuid() . '.' . $this->file->getExtension() :
            Str::slug($this->file->getBasename()) . '.' . $this->file->getExtension();

        $this->databaseUrl = $this->processDirectory . DIRECTORY_SEPARATOR . $this->outFileName;
    }

    protected function process(): bool
    {
        if (null !== $this->file) {

            if (!$this->sendFile()) {
                throw new \Exception('Error upload file.');
            }

            if ($this->scenario == self::SCENARIO_UPDATE) {
                $this->deletePreviousFiles();
            }

            $this->mediafileModel->url = $this->databaseUrl;
            $this->mediafileModel->filename = $this->outFileName;
            $this->mediafileModel->size = $this->file->getSize();
            $this->mediafileModel->type = $this->file->getMimeType();
            $this->mediafileModel->disk = Storage::getDefaultDriver();
            $this->mediafileModel->driver = Storage::getConfig()['driver'];
        }

        $this->mediafileModel->alt = $this->data['alt'];
        $this->mediafileModel->title = $this->data['title'];
        $this->mediafileModel->description = $this->data['description'];

        if (!$this->mediafileModel->save()) {
            throw new \Exception('Error save file data in database.');
        }
    }
}
