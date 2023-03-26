<?php

namespace App\Services\Uploader\src\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Services\Uploader\src\Models\Mediafile;

/**
 * Class DownloadController
 * @package App\Services\Uploader\src\Http\Controllers
 */
class DownloadController extends BaseController
{
    public function download(int $id)
    {
        $fileModel = Mediafile::find($id);
        return Storage::disk($fileModel->disk)->download($fileModel->path, $fileModel->file_name);
    }
}
