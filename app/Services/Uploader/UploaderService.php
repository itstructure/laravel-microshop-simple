<?php

namespace App\Services\Uploader;

use App\Services\Uploader\Processors\{LocalProcessor, S3Processor, BaseProcessor};

class UploaderService
{
    const STORAGE_TYPE_LOCAL = 'local';
    const STORAGE_TYPE_S3 = 's3';

    /**
     * @var string
     */
    private $storageType;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var array
     */
    private $baseConfig;

    /**
     * @var array
     */
    private $localConfig;

    /**
     * @var array
     */
    private $s3Config;

    /**
     * @var BaseProcessor
     */
    private $processor;

    public static function getInstance(array $config): self
    {
        $obj = new static();
        foreach ($config as $key => $value) {
            $obj->{'set' . ucfirst($key)}($value);
        }
        $obj->initProcessor();
        return $obj;
    }

    public function initProcessor(): self
    {
        switch ($this->storageType) {
            case self::STORAGE_TYPE_LOCAL:
                return $this->setProcessor(
                    LocalProcessor::getInstance(array_merge($this->baseConfig, $this->localConfig))
                );
            case self::STORAGE_TYPE_S3:
                return $this->setProcessor(
                    S3Processor::getInstance(array_merge($this->baseConfig, $this->s3Config))
                );
        }
        return $this;
    }

    public function setStorageType(string $storageType): self
    {
        $this->storageType = $storageType;
        return $this;
    }

    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function setBaseConfig(array $baseConfig): self
    {
        $this->baseConfig = $baseConfig;
        return $this;
    }

    public function setLocalConfig(array $localConfig): self
    {
        $this->localConfig = $localConfig;
        return $this;
    }

    public function setS3Config(array $s3Config): self
    {
        $this->s3Config = $s3Config;
        return $this;
    }

    public function setProcessor(BaseProcessor $processor): self
    {
        $this->processor = $processor;
        return $this;
    }

    public function getProcessor(): BaseProcessor
    {
        return $this->processor;
    }
}