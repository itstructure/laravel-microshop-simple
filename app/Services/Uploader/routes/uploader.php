<?php

use Illuminate\Support\Facades\Route;
use App\Services\Uploader\src\Http\Controllers\{
    UploadController, DownloadController, Managers\FileManagerController, Managers\UploadManagerController
};

Route::group([
        'prefix' => 'uploader',
        'middleware' => array_merge(
            !empty(config('uploader.routing')) && is_array(config('uploader.routing.middlewares'))
                ? config('uploader.routing.middlewares')
                : []
        )
    ], function () {


    /* UPLOADING */
    Route::group(['prefix' => 'file'], function () {

        Route::post('upload', [UploadController::class, 'upload'])
            ->name('uploader_file_upload');

        Route::post('update', [UploadController::class, 'update'])
            ->name('uploader_file_update');

        Route::post('delete', [UploadController::class, 'delete'])
            ->name('uploader_file_delete');

        Route::get('download', [DownloadController::class, 'download'])
            ->name('uploader_file_download');
    });


    /* MANAGERS */
    Route::group(['prefix' => 'managers'], function () {

        Route::get('file-manager', [FileManagerController::class, 'index'])
            ->name('uploader_managers_filemanager');

        Route::get('upload-manager', [UploadManagerController::class, 'index'])
            ->name('uploader_managers_uploadmanager');
    });
});
