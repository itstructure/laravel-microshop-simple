<?php

namespace App\Services\Uploader\src;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Services\Uploader\src\Facades\Uploader;

/**
 * Class UploadServiceProvider
 * @package App\Services\Uploader\src
 */
class UploadServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        /*$this->app->singleton('uploader', function ($app) {
            return UploadService::getInstance($app['config']['uploader']);
        });*/

        $this->app->bind('uploader', function ($app) {
            return UploadService::getInstance($app['config']['uploader']);
        });
        AliasLoader::getInstance()->alias('Uploader', Uploader::class);

        //$this->registerCommands();
    }

    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot()
    {
        // Loading settings
        //$this->loadViews();
        //$this->loadTranslations();
        $this->loadMigrations();


        // Publish settings
        $this->publishConfig();
        //$this->publishViews();
        //$this->publishTranslations();
        $this->publishMigrations();


        // Global view's params
        //View::share('rbacRowsPerPage', config('rbac.rowsPerPage', Grid::INIT_ROWS_PER_PAGE));
    }


    /*
    |--------------------------------------------------------------------------
    | COMMAND SETTINGS
    |--------------------------------------------------------------------------
    */

    /**
     * Register commands.
     * @return void
     */
    private function registerCommands(): void
    {
        //$this->commands(Commands\PublishCommand::class);
    }


    /*
    |--------------------------------------------------------------------------
    | LOADING SETTINGS
    |--------------------------------------------------------------------------
    */

    /**
     * Set directory to load views.
     * @return void
     */
    private function loadViews(): void
    {
        $this->loadViewsFrom($this->packagePath('resources/views'), 'uploader');
    }

    /**
     * Set directory to load translations.
     * @return void
     */
    private function loadTranslations(): void
    {
        $this->loadTranslationsFrom($this->packagePath('resources/lang'), 'uploader');
    }

    /**
     * Set directory to load migrations.
     * @return void
     */
    private function loadMigrations(): void
    {
        $this->loadMigrationsFrom($this->packagePath('database/migrations'));
    }


    /*
    |--------------------------------------------------------------------------
    | PUBLISH SETTINGS
    |--------------------------------------------------------------------------
    */

    /**
     * Publish config.
     * @return void
     */
    private function publishConfig(): void
    {
        $configPath = $this->packagePath('config/uploader.php');

        $this->publishes([
            $configPath => config_path('uploader.php'),
        ], 'config');

        $this->mergeConfigFrom($configPath, 'uploader');
    }

    /**
     * Publish views.
     * @return void
     */
    private function publishViews(): void
    {
        $this->publishes([
            $this->packagePath('resources/views') => resource_path('views/vendor/uploader'),
        ], 'views');
    }

    /**
     * Publish translations.
     * @return void
     */
    private function publishTranslations(): void
    {
        $this->publishes([
            $this->packagePath('resources/lang') => resource_path('lang/vendor/uploader'),
        ], 'lang');
    }

    /**
     * Publish migrations.
     * @return void
     */
    private function publishMigrations(): void
    {
        $this->publishes([
            $this->packagePath('database/migrations') => database_path('migrations'),
        ], 'migrations');
    }


    /*
    |--------------------------------------------------------------------------
    | OTHER SETTINGS
    |--------------------------------------------------------------------------
    */

    /**
     * Get package path.
     * @param $path
     * @return string
     */
    private function packagePath($path): string
    {
        return __DIR__ . '/../' . $path;
    }
}
