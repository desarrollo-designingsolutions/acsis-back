<?php

namespace App\Providers;

use App\Helpers\RoutesApi;
use App\Services\CacheService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public static string $controllerNamespace = '';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar CacheService en el contenedor
        $this->app->singleton(CacheService::class, function ($app) {
            return new CacheService;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            // Para las apis que no requieran auth
            $routesApi = RoutesApi::ROUTES_API;

            foreach ($routesApi as $route) {
                if (file_exists(base_path($route))) {
                    Route::prefix('api')
                        ->namespace(static::$controllerNamespace)
                        ->middleware(['api'])
                        ->group(base_path($route));
                }
            }

            // Para las apis que si requieran auth
            $routesAuthApi = RoutesApi::ROUTES_AUTH_API;

            foreach ($routesAuthApi as $route) {
                if (file_exists(base_path($route))) {
                    Route::prefix('api')
                        ->namespace(static::$controllerNamespace)
                        ->middleware(['auth:api'])
                        ->group(base_path($route));
                }
            }
        });
    }
}
