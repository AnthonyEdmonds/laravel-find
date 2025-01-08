<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Models;

use AnthonyEdmonds\LaravelFind\Tests\Factories\ExcludedFactory;

class Excluded extends Author
{
    // Factory
    protected static function newFactory(): ExcludedFactory
    {
        return new ExcludedFactory();
    }

    // LaravelFind
    public static function excludeFromFindAny(): bool
    {
        return true;
    }
}
