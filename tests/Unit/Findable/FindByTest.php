<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Unit\Findable;

use AnthonyEdmonds\LaravelFind\Tests\Models\Author;
use AnthonyEdmonds\LaravelFind\Tests\TestCase;
use Illuminate\Support\Collection;

class FindByTest extends TestCase
{
    protected Author $author;

    protected Author $unexpectedAuthor;

    protected Collection $results;

    protected function setUp(): void
    {
        parent::setUp();

        $this->author = Author::factory()
            ->withName('Beesworth Bobbington')
            ->create();

        $this->unexpectedAuthor = Author::factory()->create();

        $this->results = Author::findBy('Bee')->get();
    }

    public function testSelectsLabel(): void
    {
        $this->assertEquals(
            $this->author->name,
            $this->results->first()->label,
        );
    }

    public function testSelectsDescription(): void
    {
        $this->assertEquals(
            'An author',
            $this->results->first()->description,
        );
    }

    public function testReplacesLinkPlaceholders(): void
    {
        $this->assertEquals(
            'https://my-link/' . $this->author->id,
            $this->results->first()->link,
        );
    }

    public function testAddsFilters(): void
    {
        $this->assertFalse(
            $this->results->where('id', '=', $this->unexpectedAuthor)->isNotEmpty(),
        );
    }
}
