<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Factories;

use AnthonyEdmonds\LaravelFind\Tests\Models\Author;
use AnthonyEdmonds\LaravelFind\Tests\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'author_id' => Author::factory(),
            'title' => $this->faker->unique()->words(3, true),
        ];
    }

    public function forAuthor(Author $author): self
    {
        return $this->state([
            'author_id' => $author->id,
        ]);
    }

    public function withTitle(string $title): self
    {
        return $this->state([
            'title' => $title,
        ]);
    }
}
