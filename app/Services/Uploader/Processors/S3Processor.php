<?php

namespace App\Services\Uploader\Processors;

class S3Processor extends BaseProcessor
{
    /************************* CONFIG ATTRIBUTES *************************/
    /**
     * @var string
     */
    private $clientVersion;

    /**
     * @var array
     */
    private $credentials;

    /**
     * @var string
     */
    private $region;

    /**
     * @var string
     */
    private $defaultBucket;

    /**
     * @var array
     */
    private $bucketsMap;

    /************************* PROCESS ATTRIBUTES ************************/

    /************************* CONFIG SETTERS ****************************/
    /**
     * @param string $clientVersion
     * @return S3Processor
     */
    public function setClientVersion(string $clientVersion): self
    {
        $this->clientVersion = $clientVersion;
        return $this;
    }

    /**
     * @param array $credentials
     * @return S3Processor
     */
    public function setCredentials(array $credentials): self
    {
        $this->credentials = $credentials;
        return $this;
    }

    /**
     * @param string $region
     * @return S3Processor
     */
    public function setRegion(string $region): self
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @param string $defaultBucket
     * @return S3Processor
     */
    public function setDefaultBucket(string $defaultBucket): self
    {
        $this->defaultBucket = $defaultBucket;
        return $this;
    }

    /**
     * @param array $bucketsMap
     * @return S3Processor
     */
    public function setBucketsMap(array $bucketsMap): self
    {
        $this->bucketsMap = $bucketsMap;
        return $this;
    }


    /************************* PROCESS METHODS ***************************/
}