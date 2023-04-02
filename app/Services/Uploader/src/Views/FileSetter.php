<?php

namespace App\Services\Uploader\src\Views;

class FileSetter
{
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
     * @return string
     */
    public function render(): string
    {
        return view('uploader::file_setter', [

        ])->render();
    }
}