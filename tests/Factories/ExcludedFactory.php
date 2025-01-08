<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Factories;

use AnthonyEdmonds\LaravelFind\Tests\Models\Excluded;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExcludedFactory extends Factory
{
    protected $model = Excluded::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
        ];
    }

    public function withName(string $name): self
    {
        return $this->state([
            'name' => $name,
        ]);
    }
}
