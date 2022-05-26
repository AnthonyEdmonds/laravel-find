<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Unit\Find;

use AnthonyEdmonds\LaravelFind\Tests\Models\Author;
use AnthonyEdmonds\LaravelFind\Tests\TestCase;

class FindTest extends TestCase
{
    public function testBlah()
    {
        Author::create([
            'name' => 'Bob'
        ]);
    }
}
