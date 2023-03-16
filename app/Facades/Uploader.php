<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Uploader
 * @package App\Facades
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
