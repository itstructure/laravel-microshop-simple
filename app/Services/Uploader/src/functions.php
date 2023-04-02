<?php

use App\Services\Uploader\src\Views\FileSetter;

/**
 * @param array $config
 * @return string
 */
function file_setter(array $config)
{
    return FileSetter::getInstance($config)->render();
}
