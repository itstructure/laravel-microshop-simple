<?php

namespace App\Services\Uploader\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Uploader\Processors\BaseProcessor;

class Album extends Model
{
    const ALBUM_TYPE_IMAGE = BaseProcessor::FILE_TYPE_IMAGE . 'Album';
    const ALBUM_TYPE_AUDIO = BaseProcessor::FILE_TYPE_AUDIO . 'Album';
    const ALBUM_TYPE_VIDEO = BaseProcessor::FILE_TYPE_VIDEO . 'Album';
    const ALBUM_TYPE_APP   = BaseProcessor::FILE_TYPE_APP . 'Album';
    const ALBUM_TYPE_TEXT  = BaseProcessor::FILE_TYPE_TEXT . 'Album';
    const ALBUM_TYPE_OTHER = BaseProcessor::FILE_TYPE_OTHER . 'Album';

    protected $table = 'albums';

    protected $fillable = ['title', 'description', 'type'];
}
