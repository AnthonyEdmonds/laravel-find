<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Factories;

use AnthonyEdmonds\LaravelFind\Tests\Models\Author;
use AnthonyEdmonds\LaravelFind\Tests\Models\Book;
use AnthonyEdmonds\LaravelFind\Tests\Models\Chapter;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChapterFactory extends Factory
{
    protected $model = Chapter::class;

    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'title' => $this->faker->unique()->words(3, true),
            'number' => $this->faker->unique()->numberBetween(0, 99),
        ];
    }

    public function forBook(Book $book): self
    {
        return $this->state([
            'book_id' => $book->id,
        ]);
    }
}
