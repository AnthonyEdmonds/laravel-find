<?php

namespace AnthonyEdmonds\LaravelFind;

use Illuminate\Support\ServiceProvider;

class FinderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/components' => resource_path('views/vendor/laravel-find'),
        ], 'laravel-find');

        $this->loadViewsFrom(
            __DIR__,
            'laravel-find',
        );
    }
}
