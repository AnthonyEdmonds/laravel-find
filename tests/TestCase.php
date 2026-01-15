<?php

namespace AnthonyEdmonds\LaravelFind\Tests;

use AnthonyEdmonds\LaravelFind\FinderServiceProvider;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/requests/index', 'a@b')->name('requests.index');
    }

    protected function getPackageProviders($app): array
    {
        return [
            FinderServiceProvider::class,
        ];
    }
}
