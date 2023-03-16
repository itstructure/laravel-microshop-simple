<?php

namespace App\Services\Uploader\Models;

use Illuminate\Database\Eloquent\Model;

class Mediafile extends Model
{
    protected $table = 'mediafiles';

    protected $fillable = ['filename', 'type', 'url', 'alt', 'size', 'title', 'description', 'thumbs', 'disk', 'driver'];

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getDisk(): string
    {
        return $this->disk;
    }

    public function getDriver(): string
    {
        return $this->driver;
    }
}
