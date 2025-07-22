<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Register bindings, if needed
    }

    /**
     * Bootstrap any route services.
     *
     * This is where we define route middleware aliases like 'admin'.
     *
     * @return void
     */
    public function boot(): void
    {
        // Register custom middleware aliases
        Route::aliasMiddleware('admin', AdminMiddleware::class);
    }
}
