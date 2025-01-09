<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Unit\Findable;

use AnthonyEdmonds\LaravelFind\Tests\Models\Author;
use AnthonyEdmonds\LaravelFind\Tests\TestCase;

class TableNameTest extends TestCase
{
    public function testReturnsTableName(): void
    {
        $this->assertEquals(
            'authors',
            Author::tableName(),
        );
    }
}
