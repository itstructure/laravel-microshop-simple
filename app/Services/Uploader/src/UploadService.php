<?php

namespace App\Services\Uploader\src;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\MessageBag;
use App\Services\Uploader\src\Processors\{
    BaseProcessor, SaveProcessor, UploadProcessor, UpdateProcessor, DeleteProcessor
};
use App\Services\Uploader\src\Models\Mediafile;

/**
 * Class UploadService
 * @package App\Services\Uploader\src
 * @author Andrey Girnik <girnikandrey@gmail.com>
 */
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

    /**
     * @param array $config
     * @return UploadService
     */
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
    public function upload(array $data, UploadedFile $file = null): bool
    {
        $this->processor = UploadProcessor::getInstance($this->config['processor'])
            ->setMediafileModel(new Mediafile())
            ->setData($data)
            ->setFile($file);

        return $this->save();
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
        $this->processor = UpdateProcessor::getInstance($this->config['processor'])
            ->setMediafileModel(Mediafile::find($id))
            ->setData($data)
            ->setFile($file);

        return $this->save();
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
     * @param string|null $key
     * @return array
     */
    public function getConfig(string $key = null)
    {
        return !empty($key) ? $this->config[$key] : $this->config;
    }

    /**
     * UploadService constructor.
     * @param array $config
     */
    private function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @return bool
     */
    private function save(): bool
    {
        if (!$this->processor->run()) {
            return false;
        }

        if ($this->processor->getMediafileModel()->isImage()) {
            $this->processor->createThumbs();
        }

        return true;
    }
}
