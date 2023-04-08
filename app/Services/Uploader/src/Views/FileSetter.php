<?php

namespace App\Services\Uploader\src\Views;

use Illuminate\Database\Eloquent\Model;

class FileSetter
{
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
        return view('uploader::file_setter', [
            'attribute' => $this->attribute,
            'value' => !empty($this->model)
                ? $this->model->{$this->attribute}
                : $this->value
        ])->render();
    }

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
}