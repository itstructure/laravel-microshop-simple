<?php

namespace App\Services\Uploader\Processors;

use App\Services\Uploader\UploaderService;

class S3Processor extends BaseProcessor
{
    /************************* CONFIG ATTRIBUTES *************************/
    /**
     * @var array
     */
    private $bucketsMap;

    /************************* PROCESS ATTRIBUTES ************************/

    /************************* CONFIG SETTERS ****************************/
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