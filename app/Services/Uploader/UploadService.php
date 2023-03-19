<?php

namespace App\Services\Uploader;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\MessageBag;
use App\Services\Uploader\Processors\{BaseProcessor, UploadProcessor, UpdateProcessor, DeleteProcessor};
use App\Services\Uploader\Models\Mediafile;

class UploadService
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var BaseProcessor
     */
    private $processor;

    public static function getInstance(array $config): self
    {
        return new static($config);
    }

    /**
     * @param array $data
     * @param UploadedFile $file
     * @throws Exception
     * @return bool
     */
    public function upload(array $data, UploadedFile $file): bool
    {
        $this->processor = UploadProcessor::getInstance($this->config)
            ->setMediafileModel(new Mediafile())
            ->setData($data)
            ->setFile($file);
        return $this->processor->run();
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
        $this->processor = UpdateProcessor::getInstance($this->config)
            ->setMediafileModel(Mediafile::find($Id))
            ->setData($data)
            ->setFile($file);
        return $this->processor->run();
    }

    /**
     * @param int $Id
     * @throws Exception
     * @return bool
     */
    public function delete(int $Id): bool
    {
        $this->processor = DeleteProcessor::getInstance()
            ->setMediafileModel(Mediafile::find($Id));
        return $this->processor->run();
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !is_null($this->processor->getErrors());
    }

    /**
     * @return MessageBag
     */
    public function getErrors(): MessageBag
    {
        return $this->processor->getErrors();
    }

    /**
     * UploadService constructor.
     * @param array $config
     */
    private function __construct(array $config)
    {
        $this->config = $config;
    }
}
