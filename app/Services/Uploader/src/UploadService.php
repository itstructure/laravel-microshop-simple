<?php

namespace App\Services\Uploader\src;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\MessageBag;
use App\Services\Uploader\src\Processors\{
    BaseProcessor, SaveProcessor, UploadProcessor, UpdateProcessor, DeleteProcessor
};
use App\Services\Uploader\src\Models\Mediafile;

class UploadService
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var SaveProcessor|BaseProcessor
     */
    private $processor;

    public static function getInstance(array $config = []): self
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
     * @param int $id
     * @param array $data
     * @param UploadedFile|null $file
     * @throws Exception
     * @return bool
     */
    public function update(int $id, array $data, UploadedFile $file = null): bool
    {
        $this->processor = UpdateProcessor::getInstance($this->config)
            ->setMediafileModel(Mediafile::find($id))
            ->setData($data)
            ->setFile($file);
        return $this->processor->run();
    }

    /**
     * @param int $id
     * @throws Exception
     * @return bool
     */
    public function delete(int $id): bool
    {
        $this->processor = DeleteProcessor::getInstance()
            ->setMediafileModel(Mediafile::find($id));
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
     * @return MessageBag|null
     */
    public function getErrors(): ?MessageBag
    {
        return $this->processor->getErrors();
    }

    /**
     * UploadService constructor.
     * @param array $config
     */
    private function __construct(array $config = [])
    {
        $this->config = $config;
    }
}
