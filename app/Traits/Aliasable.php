<?php

namespace App\Traits;

/**
 * Class Aliasable
 * @package App\Traits
 */
trait Aliasable
{
    /**
     * @param string $alias
     * @return mixed
     */
    public static function getByAlias(string $alias)
    {
        return static::where('alias', $alias)->first();
    }
}