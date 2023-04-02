<?php

namespace App\Services\Uploader\src\Processors;

use Exception;
use Illuminate\Support\MessageBag;
use App\Services\Uploader\src\Models\Mediafile;

/**
 * Class BaseProcessor
 * @package App\Services\Uploader\src\Processors
 * @author Andrey Girnik <girnikandrey@gmail.com>
 */
abstract class BaseProcessor
{
    /************************* PROCESS ATTRIBUTES *************************/
    /**
     * @var string
     */
    protected $currentDisk;

    /**
     * @var Mediafile
     */
    protected $mediafileModel;

    /**
     * @var string
     */
    protected $processDirectory;

    /**
     * @var MessageBag|null
     */
    protected $errors;


    /************************* ABSTRACT METHODS ***************************/
    abstract protected function setProcessParams(): void;

    /**
     * @throws Exception
     * @return bool
     */
    abstract protected function process(): bool;

    abstract protected function afterProcess(): void;


    /********************** PROCESS PUBLIC METHODS ***********************/
    /**
     * @param array $config
     * @return static
     */
    public static function getInstance(array $config = [])
    {
        $obj = new static();
        foreach ($config as $key => $value) {
            $obj->{'set' . ucfirst($key)}($value);
        }
        return $obj;
    }

    /**
     * @throws Exception
     * @return bool
     */
    public function run(): bool
    {
        $this->setProcessParams();
        $this->process();
        $this->afterProcess();
        return true;
    }

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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->mediafileModel->id;
    }

    /**
     * @return MessageBag|null
     */
    public function getErrors(): ?MessageBag
    {
        return $this->errors;
    }
}
