<?php

namespace App\Services\Uploader\Models;

use Illuminate\Database\Eloquent\Model;

class Mediafile extends Model
{
    protected $table = 'mediafiles';

    protected $fillable = ['filename', 'type', 'url', 'alt', 'size', 'title', 'description', 'thumbs', 'storage'];

    public function getUrl(): string
    {
        return $this->url;
    }
}
