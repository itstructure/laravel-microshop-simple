<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Order
 * @package App\Facades
 */
class Order extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'order';
    }
}