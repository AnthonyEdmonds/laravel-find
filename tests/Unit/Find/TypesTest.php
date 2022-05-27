<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Unit\Find;

use AnthonyEdmonds\LaravelFind\Find;
use AnthonyEdmonds\LaravelFind\Tests\Models\Author;
use AnthonyEdmonds\LaravelFind\Tests\Models\Book;
use AnthonyEdmonds\LaravelFind\Tests\Models\Chapter;
use AnthonyEdmonds\LaravelFind\Tests\TestCase;

class TypesTest extends TestCase
{
    public function testListsAnythingWhenEnabled(): void
    {
        $this->assertArrayHasKey(
            'any',
            Find::types()
        );
    }

    public function testDoesntListAnythingWhenDisabled(): void
    {
        $this->app['config']->set('laravel-find.anything-key', false);

        $this->assertArrayNotHasKey(
            'any',
            Find::types()
        );
    }

    public function testListsFindableModels(): void
    {
        $this->assertArrayHasKey(
            Author::class,
            Find::types()
        );

        $this->assertArrayHasKey(
            Chapter::class,
            Find::types()
        );
    }

    public function testDoesntListUnfindable(): void
    {
        $this->assertArrayNotHasKey(
            Book::class,
            Find::types()
        );
    }
}
