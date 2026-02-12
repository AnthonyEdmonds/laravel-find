<?php

namespace AnthonyEdmonds\LaravelFind;

use Illuminate\Support\ServiceProvider;

class FinderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config.php',
            'laravel-find',
        );
    }

    public function boot(): void
    {
        $template = config('laravel-find.template') ?? 'default';

        $this->publishes([
            __DIR__ . 'config.php' => config_path('laravel-find.php'),
        ], 'laravel-find-config');

        $this->publishes([
            __DIR__ . '/views' => resource_path('views/vendor/laravel-find'),
        ], 'laravel-find-views');

        $this->loadViewsFrom(
            __DIR__ . "/views/$template",
            'laravel-find',
        );
    }
}
