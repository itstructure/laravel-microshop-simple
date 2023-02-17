<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Class Titleable
 * @package App\Traits
 */
trait Titleable
{
    /**
     * @param $value
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['alias'] = Str::slug($value, '-');
    }
}