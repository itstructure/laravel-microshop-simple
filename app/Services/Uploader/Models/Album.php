<?php

namespace App\Services\Uploader\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Uploader\UploadService;

class Album extends Model
{
    const ALBUM_TYPE_IMAGE = UploadService::FILE_TYPE_IMAGE . 'Album';
    const ALBUM_TYPE_AUDIO = UploadService::FILE_TYPE_AUDIO . 'Album';
    const ALBUM_TYPE_VIDEO = UploadService::FILE_TYPE_VIDEO . 'Album';
    const ALBUM_TYPE_APP   = UploadService::FILE_TYPE_APP . 'Album';
    const ALBUM_TYPE_TEXT  = UploadService::FILE_TYPE_TEXT . 'Album';
    const ALBUM_TYPE_OTHER = UploadService::FILE_TYPE_OTHER . 'Album';

    protected $table = 'albums';

    protected $fillable = ['title', 'description', 'type'];
}
