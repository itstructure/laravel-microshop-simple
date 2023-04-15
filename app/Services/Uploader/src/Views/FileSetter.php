<?php

namespace App\Services\Uploader\src\Views;

use Illuminate\Database\Eloquent\Model;

class FileSetter
{
    const INSERTED_DATA_ID = 'id';
    const INSERTED_DATA_PATH = 'path';

    /************************* CONFIG ATTRIBUTES *************************/
    /**
     * @var Model
     */
    private $model;

    /**
     * @var string
     */
    private $attribute;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string|int
     */
    private $inputId;

    /**
     * @var string|int
     */
    private $openButtonId;

    /**
     * @var string
     */
    private $openButtonName = 'Browse';

    /**
     * @var string
     */
    private $clearButtonName = 'Clear';

    /**
     * @var bool
     */
    private $deleteBoxDisplay = false;

    /**
     * @var string
     */
    private $deleteBoxName = 'Delete';

    /**
     * @var string
     */
    private $deleteBoxAttribute = 'delete[]';

    /**
     * @var int
     */
    private $deleteBoxValue = 1;

    /**
     * @var string
     */
    private $mediafileContainerId;

    /**
     * @var string
     */
    private $titleContainerId;

    /**
     * @var string
     */
    private $descriptionContainerId;

    /**
     * @var string
     */
    private $callbackBeforeInsert;

    /**
     * @var string
     */
    private $insertedDataType = self::INSERTED_DATA_ID;

    /**
     * @var string
     */
    private $ownerName;

    /**
     * @var int
     */
    private $ownerId;

    /**
     * @var string
     */
    private $ownerAttribute;

    /**
     * @var string
     */
    private $neededFileType;

    /**
     * @var string
     */
    private $subDir;


    /************************* PROCESS ATTRIBUTES *************************/
    /**
     * @var int
     */
    public static $counter = 0;

    /**
     * @var string
     */
    public static $autoInputIdPrefix = 'w';


    /********************** PROCESS PUBLIC METHODS ***********************/
    /**
     * @param array $config
     * @return FileSetter
     */
    public static function getInstance(array $config): self
    {
        $obj = new static();
        foreach ($config as $key => $value) {
            $obj->{'set' . ucfirst($key)}($value);
        }
        return $obj;
    }

    /**
     * @param array $config
     * @return string
     */
    public static function run(array $config)
    {
        return static::getInstance($config)->render();
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return view('uploader::file_setter.index', [
            'fileManagerUrl' => '???',
            'attribute' => $this->attribute,
            'value' => !empty($this->model)
                ? $this->model->{$this->attribute}
                : $this->value,
            'inputId' => $this->getInputId(),
            'openButtonId' => $this->getOpenButtonId(),
            'openButtonName' => $this->openButtonName,
            'clearButtonName' => $this->clearButtonName,
            'deleteBoxDisplay' => $this->deleteBoxDisplay,
            'deleteBoxName' => $this->deleteBoxName,
            'deleteBoxAttribute' => $this->deleteBoxAttribute,
            'deleteBoxValue' => $this->deleteBoxValue,
            'mediafileContainerId' => $this->mediafileContainerId,
            'titleContainerId' => $this->titleContainerId,
            'descriptionContainerId' => $this->descriptionContainerId,
            'insertedDataType' => $this->insertedDataType,
            'ownerName' => $this->ownerName,
            'ownerId' => $this->ownerId,
            'ownerAttribute' => $this->ownerAttribute,
            'neededFileType' => $this->neededFileType,
            'subDir' => $this->subDir,
        ])->render();
    }


    /************************* CONFIG SETTERS *****************************/
    /**
     * @param Model $model
     * @return FileSetter
     */
    public function setModel(Model $model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @param string $attribute
     * @return FileSetter
     */
    public function setAttribute(string $attribute): self
    {
        $this->attribute = $attribute;
        return $this;
    }

    /**
     * @param $value
     * @return FileSetter
     */
    public function setValue($value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return FileSetter
     */
    public function setInputId(string $value): self
    {
        $this->inputId = $value;
        return $this;
    }

    /**
     * @param string $openButtonId
     * @return FileSetter
     */
    public function setOpenButtonId(string $openButtonId): self
    {
        $this->openButtonId = $openButtonId;
        return $this;
    }

    /**
     * @param string $openButtonName
     * @return FileSetter
     */
    public function setOpenButtonName(string $openButtonName): self
    {
        $this->openButtonName = $openButtonName;
        return $this;
    }

    /**
     * @param string $clearButtonName
     * @return FileSetter
     */
    public function setClearButtonName(string $clearButtonName): self
    {
        $this->clearButtonName = $clearButtonName;
        return $this;
    }

    /**
     * @param bool $deleteBoxDisplay
     * @return FileSetter
     */
    public function setDeleteBoxDisplay(bool $deleteBoxDisplay): self
    {
        $this->deleteBoxDisplay = $deleteBoxDisplay;
        return $this;
    }

    /**
     * @param string $deleteBoxName
     * @return FileSetter
     */
    public function setDeleteBoxName(string $deleteBoxName): self
    {
        $this->deleteBoxName = $deleteBoxName;
        return $this;
    }

    /**
     * @param string $deleteBoxAttribute
     * @return FileSetter
     */
    public function setDeleteBoxAttribute(string $deleteBoxAttribute): self
    {
        $this->deleteBoxAttribute = $deleteBoxAttribute;
        return $this;
    }

    /**
     * @param mixed $deleteBoxValue
     * @return FileSetter
     */
    public function setDeleteBoxValue($deleteBoxValue): self
    {
        $this->deleteBoxValue = $deleteBoxValue;
        return $this;
    }

    /**
     * @param string $mediafileContainerId
     * @return FileSetter
     */
    public function setMediafileContainerId(string $mediafileContainerId): self
    {
        $this->mediafileContainerId = $mediafileContainerId;
        return $this;
    }

    /**
     * @param string $titleContainerId
     * @return FileSetter
     */
    public function setTitleContainerId(string $titleContainerId): self
    {
        $this->titleContainerId = $titleContainerId;
        return $this;
    }

    /**
     * @param string $descriptionContainerId
     * @return FileSetter
     */
    public function setDescriptionContainerId(string $descriptionContainerId): self
    {
        $this->descriptionContainerId = $descriptionContainerId;
        return $this;
    }

    /**
     * @param string $callbackBeforeInsert
     * @return FileSetter
     */
    public function setCallbackBeforeInsert(string $callbackBeforeInsert): self
    {
        $this->callbackBeforeInsert = $callbackBeforeInsert;
        return $this;
    }

    /**
     * @param string $insertedDataType
     * @return FileSetter
     */
    public function setInsertedDataType(string $insertedDataType): self
    {
        $this->insertedDataType = $insertedDataType;
        return $this;
    }

    /**
     * @param string $ownerName
     * @return FileSetter
     */
    public function setOwnerName(string $ownerName): self
    {
        $this->ownerName = $ownerName;
        return $this;
    }

    /**
     * @param int $ownerId
     * @return FileSetter
     */
    public function setOwnerId(int $ownerId): self
    {
        $this->ownerId = $ownerId;
        return $this;
    }

    /**
     * @param string $ownerAttribute
     * @return FileSetter
     */
    public function setOwnerAttribute(string $ownerAttribute): self
    {
        $this->ownerAttribute = $ownerAttribute;
        return $this;
    }

    /**
     * @param string $neededFileType
     * @return FileSetter
     */
    public function setNeededFileType(string $neededFileType): self
    {
        $this->neededFileType = $neededFileType;
        return $this;
    }

    /**
     * @param string $subDir
     * @return FileSetter
     */
    public function setSubDir(string $subDir): self
    {
        $this->subDir = $subDir;
        return $this;
    }


    /********************** PROCESS INTERNAL METHODS *********************/
    /**
     * @return string
     */
    private function getInputId(): string
    {
        if (empty($this->inputId)) {
            $this->inputId = !empty($this->model)
                ? $this->getInputIdByModel()
                : $this->getInputIdGenerated();
        }
        return $this->inputId;
    }

    /**
     * @return string
     */
    private function getInputIdByModel(): string
    {
        return $this->model->getTable() . '-' . $this->attribute
        . (!empty($this->model->getKey()) ? '-' . $this->model->getKey() : '');
    }

    /**
     * @return string
     */
    private function getInputIdGenerated(): string
    {
        return static::$autoInputIdPrefix . static::$counter++;
    }

    /**
     * @return string
     */
    private function getOpenButtonId(): string
    {
        if (empty($this->openButtonId)) {
            $this->openButtonId = $this->inputId . '-btn';
        }
        return $this->openButtonId;
    }
}
