<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Unit\Finder;

use AnthonyEdmonds\LaravelFind\Finder;
use AnthonyEdmonds\LaravelFind\Tests\TestCase;
use AnthonyEdmonds\LaravelFind\Tests\TestFinder;

class CaptionTest extends TestCase
{
    protected Finder $finder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->finder = new TestFinder();
        $this->finder->currentFilter = 'author';
        $this->finder->currentStatus = 'open';
        $this->finder->currentSort = 'oldest';
    }

    public function test(): void
    {
        $this->assertEquals(
            'Open requests I created by oldest',
            $this->finder->caption(),
        );
    }
}
