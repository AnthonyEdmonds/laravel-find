<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Unit\Finder;

use AnthonyEdmonds\LaravelFind\Tests\TestCase;
use AnthonyEdmonds\LaravelFind\Tests\TestFinder;

class ClearLabelTest extends TestCase
{
    protected TestFinder $finder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->finder = new TestFinder();
    }

    public function test(): void
    {
        $this->assertEquals(
            'Clear all applied filters, searches, and sorts',
            $this->finder->clearLabel(),
        );
    }
}
