<?php

namespace App\Services\Uploader\Processors;

use App\Services\Uploader\Models\Mediafile;

abstract class BaseProcessor
{
    /************************* PROCESS ATTRIBUTES *************************/
    /**
     * @var Mediafile
     */
    protected $mediafileModel;

    /**
     * @var string
     */
    protected $processDirectory;

    /**
     * @param array $config
     * @return static
     */
    public static function getInstance(array $config)
    {
        $obj = new static();
        foreach ($config as $key => $value) {
            $obj->{'set' . ucfirst($key)}($value);
        }
        return $obj;
    }

    /********************** PROCESS PUBLIC METHODS ***********************/
    /**
     * @param Mediafile $model
     * @return $this
     */
    public function setMediafileModel(Mediafile $model)
    {
        $this->mediafileModel = $model;
        return $this;
    }

    /**
     * @return Mediafile
     */
    public function getMediafileModel(): Mediafile
    {
        return $this->mediafileModel;
    }
}
