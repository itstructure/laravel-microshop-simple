<?php

namespace App\Services\Uploader\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Uploader\Interfaces\UploadProcessorInterface;

class Album extends Model
{
    const ALBUM_TYPE_IMAGE = UploadProcessorInterface::FILE_TYPE_IMAGE . 'Album';
    const ALBUM_TYPE_AUDIO = UploadProcessorInterface::FILE_TYPE_AUDIO . 'Album';
    const ALBUM_TYPE_VIDEO = UploadProcessorInterface::FILE_TYPE_VIDEO . 'Album';
    const ALBUM_TYPE_APP   = UploadProcessorInterface::FILE_TYPE_APP . 'Album';
    const ALBUM_TYPE_TEXT  = UploadProcessorInterface::FILE_TYPE_TEXT . 'Album';
    const ALBUM_TYPE_OTHER = UploadProcessorInterface::FILE_TYPE_OTHER . 'Album';

    protected $table = 'albums';

    protected $fillable = ['title', 'description', 'type'];
}
