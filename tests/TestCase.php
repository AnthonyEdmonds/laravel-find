<?php

namespace AnthonyEdmonds\LaravelFind\Tests;

use AnthonyEdmonds\LaravelFind\FindServiceProvider;
use AnthonyEdmonds\LaravelFind\Tests\Models\Author;
use AnthonyEdmonds\LaravelFind\Tests\Models\Book;
use AnthonyEdmonds\LaravelFind\Tests\Models\Chapter;
use AnthonyEdmonds\LaravelFind\Tests\Providers\TestServiceProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'default'])->run();
    }

    protected function getPackageProviders($app): array
    {
        return [
            TestServiceProvider::class,
            FindServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'default');
        $app['config']->set('database.connections.default', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('laravel-find.base-table', 'authors');

        $app['config']->set('laravel-find.models', [
            Author::class => 'Authors by name and books',
            Book::class => 'Books by title, author, and chapters',
            Chapter::class => 'Chapters by name, number, and book',
        ]);
    }
}
