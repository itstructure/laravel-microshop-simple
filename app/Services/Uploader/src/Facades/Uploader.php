<?php

namespace App\Services\Uploader\src\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Uploader
 * @package App\Services\Uploader\src\Facades
 */
class Uploader extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'uploader';
    }
}
