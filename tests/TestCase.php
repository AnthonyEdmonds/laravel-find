<?php

namespace AnthonyEdmonds\LaravelFind\Tests;

use AnthonyEdmonds\LaravelFind\FindServiceProvider;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\Concerns\CreatesApplication;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use WithFaker;
    use LazilyRefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->useDatabasePath(base_path('/tests/Migrations'));
    }

    protected function getPackageProviders($app): array
    {
        return [
            FindServiceProvider::class,
        ];
    }
}
