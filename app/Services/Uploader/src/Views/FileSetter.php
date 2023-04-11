<?php

namespace App\Services\Uploader\src\Views;

use Illuminate\Database\Eloquent\Model;

class FileSetter
{
    public static $counter = 0;

    public static $autoInputIdPrefix = 'w';


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


    /************************* PROCESS ATTRIBUTES *************************/


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
            'attribute' => $this->attribute,
            'value' => !empty($this->model)
                ? $this->model->{$this->attribute}
                : $this->value,
            'inputId' => $this->getInputId(),
            'openButtonId' => $this->getOpenButtonId()
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
