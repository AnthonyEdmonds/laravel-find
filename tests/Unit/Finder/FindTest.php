<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Unit\Finder;

use AnthonyEdmonds\LaravelFind\FinderOutput;
use AnthonyEdmonds\LaravelFind\Tests\TestCase;
use AnthonyEdmonds\LaravelFind\Tests\TestFinder;

class FindTest extends TestCase
{
    public function test(): void
    {
        $this->assertInstanceOf(
            FinderOutput::class,
            TestFinder::find(),
        );
    }
}
