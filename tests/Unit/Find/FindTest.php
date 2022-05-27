<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Unit\Find;

use AnthonyEdmonds\LaravelFind\Find;
use AnthonyEdmonds\LaravelFind\Tests\Models\Author;
use AnthonyEdmonds\LaravelFind\Tests\Models\Book;
use AnthonyEdmonds\LaravelFind\Tests\Models\Chapter;
use AnthonyEdmonds\LaravelFind\Tests\TestCase;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Collection;

class FindTest extends TestCase
{
    protected Author $author;
    protected Book $book;
    protected Collection $chapters;

    protected function setUp(): void
    {
        parent::setUp();

        $this->author = Author::factory()
            ->withName('Beesington Norville')
            ->create();

        $this->book = Book::factory()
            ->forAuthor($this->author)
            ->withTitle('Lord of the Bees')
            ->create();

        $this->chapters = Chapter::factory()
            ->forBook($this->book)
            ->count(3)
            ->create();
    }

    public function testFindsSpecificModel(): void
    {
        $results = Find::find('bees', Chapter::class)
            ->get()
            ->pluck('label');

        foreach ($this->chapters as $chapter) {
            $this->assertTrue(
                $results->contains($chapter->title)
            );
        }
    }

    public function testFindsAnything(): void
    {
        $results = Find::find('bees', 'any')
            ->get()
            ->pluck('label');

        $this->assertTrue(
            $results->contains($this->author->name)
        );

        foreach ($this->chapters as $chapter) {
            $this->assertTrue(
                $results->contains($chapter->title)
            );
        }

        $this->assertFalse(
            $results->contains($this->book->title)
        );
    }

    public function testExceptionWhenDenied(): void
    {
        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage('You do not have permission to find a Book');

        Find::find('unholy', Book::class);
    }
}
