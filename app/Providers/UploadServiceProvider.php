<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Uploader\UploadService;

/**
 * Class UploadServiceProvider
 * @package App\Providers
 */
class UploadServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('uploader', function ($app) {
            return UploadService::getInstance($app['config']['uploader']);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
