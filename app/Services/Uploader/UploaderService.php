<?php

namespace App\Services\Uploader;

use App\Services\Uploader\Interfaces\UploadProcessorInterface;
use App\Services\Uploader\Processors\{LocalProcessor, S3Processor};

class UploaderService
{
    const STORAGE_TYPE_LOCAL = 'local';
    const STORAGE_TYPE_S3 = 's3';

    const THUMB_ALIAS_DEFAULT  = 'default';
    const THUMB_ALIAS_ORIGINAL = 'original';
    const THUMB_ALIAS_SMALL    = 'small';
    const THUMB_ALIAS_MEDIUM   = 'medium';
    const THUMB_ALIAS_LARGE    = 'large';

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
    private $thumbsConfig;

    /**
     * @var array
     */
    private $localConfig;

    /**
     * @var array
     */
    private $s3Config;

    /**
     * @var UploadProcessorInterface
     */
    private $processor;

    public static function getInstance(array $config)
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
                $this->processor = LocalProcessor::getInstance($this->localConfig);
                break;
            case self::STORAGE_TYPE_S3:
                $this->processor = S3Processor::getInstance($this->s3Config);
                break;
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

    public function setThumbsConfig(array $thumbsConfig): self
    {
        $this->thumbsConfig = $thumbsConfig;
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

    /**
     * @return UploadProcessorInterface|LocalProcessor|S3Processor
     */
    public function getProcessor(): UploadProcessorInterface
    {
        return $this->processor;
    }
}