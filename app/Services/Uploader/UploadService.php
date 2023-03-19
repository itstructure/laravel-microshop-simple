<?php

namespace App\Services\Uploader;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\MessageBag;
use App\Services\Uploader\Processors\{UploadProcessor, UpdateProcessor, DeleteProcessor};
use App\Services\Uploader\Models\Mediafile;

class UploadService
{
    /********************** PROCESS PUBLIC METHODS ***********************/
    public static function getInstance(array $config): self
    {
        $obj = new static();
        foreach ($config as $key => $value) {
            $obj->{'set' . ucfirst($key)}($value);
        }
        return $obj;
    }

    /**
     * @param array $data
     * @param UploadedFile $file
     * @throws Exception
     * @return bool
     */
    public function upload(array $data, UploadedFile $file): bool
    {
        return UploadProcessor::getInstance()
            ->setMediafileModel(new Mediafile())
            ->setData($data)
            ->setFile($file)
            ->run();
    }

    /**
     * @param int $Id
     * @param array $data
     * @param UploadedFile|null $file
     * @throws Exception
     * @return bool
     */
    public function update(int $Id, array $data, UploadedFile $file = null): bool
    {
        return UpdateProcessor::getInstance()
            ->setMediafileModel(Mediafile::find($Id))
            ->setData($data)
            ->setFile($file)
            ->run();
    }

    /**
     * @param int $Id
     * @throws Exception
     * @return bool
     */
    public function delete(int $Id): bool
    {
        return DeleteProcessor::getInstance()
            ->setMediafileModel(Mediafile::find($Id))
            ->run();
    }
}
