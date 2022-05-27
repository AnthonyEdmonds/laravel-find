<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Factories;

use AnthonyEdmonds\LaravelFind\Tests\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name()
        ];
    }

    public function withName(string $name): self
    {
        return $this->state([
            'name' => $name,
        ]);
    }
}
