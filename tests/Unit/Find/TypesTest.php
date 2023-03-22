<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Unit\Find;

use AnthonyEdmonds\LaravelFind\Find;
use AnthonyEdmonds\LaravelFind\Tests\Models\Author;
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
        $this->assertEquals(
            Author::findTypeLabel(),
            Find::types()['authors']
        );

        $this->assertEquals(
            Chapter::findTypeLabel(),
            Find::types()['chapters']
        );
    }

    public function testDoesntListUnfindable(): void
    {
        $this->assertArrayNotHasKey(
            'books',
            Find::types()
        );
    }
}
