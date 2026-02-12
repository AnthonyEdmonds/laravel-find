<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Unit\Finder;

use AnthonyEdmonds\LaravelFind\Finder;
use AnthonyEdmonds\LaravelFind\Tests\TestCase;
use AnthonyEdmonds\LaravelFind\Tests\TestFinder;

class ConstructTest extends TestCase
{
    protected Finder $finder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->finder = new TestFinder();
    }

    public function test(): void
    {
        $this->assertNull(
            $this->finder->user,
        );

        $this->assertEquals(
            TestFinder::DEFAULT_FILTER,
            $this->finder->currentFilter,
        );

        $this->assertEquals(
            '',
            $this->finder->currentSearch,
        );

        $this->assertEquals(
            TestFinder::DEFAULT_SORT,
            $this->finder->currentSort,
        );

        $this->assertEquals(
            TestFinder::DEFAULT_STATUS,
            $this->finder->currentStatus,
        );
    }
}
