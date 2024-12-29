<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
        //
         // Map API and Web routes
         $this->mapApiRoutes();
         $this->mapWebRoutes();
    }
    protected function mapApiRoutes()
    {
        Route::middleware('api')
            ->group(base_path('routes/api.php'));
    }

    /**
     * Map the Web routes.
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }
}
