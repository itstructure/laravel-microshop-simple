<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Card
 * @package App\Facades
 */
class Card extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'card';
    }
}