<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\{Schema, View};
use App\Http\View\Composers\{CategoryViewComposer, TopCardViewComposer, OrderCardViewComposer};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::defaultView('vendor.pagination.bootstrap-5');
        Paginator::defaultSimpleView('vendor.pagination.bootstrap-5');

        View::composer(
            ['home', 'product'],
            TopCardViewComposer::class
        );

        View::composer(
            ['card'],
            OrderCardViewComposer::class
        );

        View::composer(
            ['home', 'card', 'product'],
            CategoryViewComposer::class
        );
    }
}
