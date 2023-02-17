<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CardService;

/**
 * Class CardServiceProvider
 * @package App\Providers
 */
class CardServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('card', function($app)
        {
            $object = new CardService();

            foreach ($app['config']['card'] as $key => $value) {
                $object->{$key} = $value;
            }

            return $object;
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
