<?php

namespace Stackmasteraliza\ApiResponse;

use Illuminate\Support\ServiceProvider;

class ApiResponseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/api-response.php',
            'api-response'
        );

        $this->app->singleton('api-response', function ($app) {
            return new ApiResponse();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/api-response.php' => config_path('api-response.php'),
            ], 'api-response-config');
        }
    }
}
