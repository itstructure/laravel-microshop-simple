<?php

namespace App\Services\Uploader;

use Exception;
use Illuminate\Http\UploadedFile;
use App\Services\Uploader\Processors\{UploadProcessor, UpdateProcessor, DeleteProcessor};
use App\Services\Uploader\Models\Mediafile;
use App\Services\Uploader\Classes\Result;

class UploadService
{
    /**
     * @var array
     */
    private $config;

    public static function getInstance(array $config): self
    {
        return new static($config);
    }

    /**
     * @param array $data
     * @param UploadedFile $file
     * @throws Exception
     * @return Result
     */
    public function upload(array $data, UploadedFile $file): Result
    {
        $processor = UploadProcessor::getInstance($this->config)
            ->setMediafileModel(new Mediafile())
            ->setData($data)
            ->setFile($file);
        $result = new Result();
        if (!$processor->run()) {
            $result->setErrors($processor->getErrors());
            $result->setNoSuccessful();
        } else {
            $result->setSuccessful();
        }
        return $result;
    }

    /**
     * @param int $Id
     * @param array $data
     * @param UploadedFile|null $file
     * @throws Exception
     * @return Result
     */
    public function update(int $Id, array $data, UploadedFile $file = null): Result
    {
        $processor = UpdateProcessor::getInstance($this->config)
            ->setMediafileModel(Mediafile::find($Id))
            ->setData($data)
            ->setFile($file);
        $result = new Result();
        if (!$processor->run()) {
            $result->setErrors($processor->getErrors());
            $result->setNoSuccessful();
        } else {
            $result->setSuccessful();
        }
        return $result;
    }

    /**
     * @param int $Id
     * @throws Exception
     * @return Result
     */
    public function delete(int $Id): Result
    {
        $processor = DeleteProcessor::getInstance()
            ->setMediafileModel(Mediafile::find($Id));
        $result = new Result();
        if (!$processor->run()) {
            $result->setNoSuccessful();
        } else {
            $result->setSuccessful();
        }
        return $result;
    }

    private function __construct(array $config)
    {
        $this->config = $config;
    }
}
