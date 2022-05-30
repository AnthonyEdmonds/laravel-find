<?php

namespace AnthonyEdmonds\LaravelFind;

use Illuminate\Support\ServiceProvider;

class FindServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/find-config.php',
            'laravel-find'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/find-config.php' => config_path('laravel-find.php'),
        ], 'laravel-find');
    }
}
