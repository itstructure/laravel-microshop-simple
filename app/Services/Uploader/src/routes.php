<?php

use Illuminate\Support\Facades\Route;
use App\Services\Uploader\src\Http\Controllers\{UploadController, DownloadController};

Route::group([
        'prefix' => 'uploader',
        'middleware' => array_merge(
            is_array(config('uploader.routesAuthMiddlewares')) ? config('uploader.routesAuthMiddlewares') : [],
            !empty(config('uploader.routesMainPermission')) ? ['can:'.config('uploader.routesMainPermission')] : []
        )
    ], function () {

    Route::group(['prefix' => 'file'], function () {

        Route::post('upload', [UploadController::class, 'upload'])
            ->name('file_upload');

        Route::post('update', [UploadController::class, 'update'])
            ->name('file_update');

        Route::post('delete', [UploadController::class, 'delete'])
            ->name('file_delete');

        Route::get('download', [DownloadController::class, 'download'])
            ->name('file_download');
    });
});
