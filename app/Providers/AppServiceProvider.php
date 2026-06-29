<?php

namespace App\Providers;

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
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Register custom Microsoft Socialite driver
        try {
            $socialite = $this->app->make(\Laravel\Socialite\Contracts\Factory::class);
            $socialite->extend('microsoft', function ($app) use ($socialite) {
                $config = $app['config']['services.microsoft'];
                return $socialite->buildProvider(
                    \App\Services\Socialite\MicrosoftProvider::class,
                    $config
                );
            });
        } catch (\Exception $e) {
            // Silence if socialite factory is not registerable in console/tests
        }
    }
}
