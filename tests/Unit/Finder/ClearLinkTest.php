<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Unit\Finder;

use AnthonyEdmonds\LaravelFind\Tests\TestCase;
use AnthonyEdmonds\LaravelFind\Tests\TestFinder;
use Illuminate\Support\Facades\URL;

class ClearLinkTest extends TestCase
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
            URL::route(
                $this->finder->route(),
                [
                    TestFinder::KEY_FILTER => TestFinder::DEFAULT_FILTER,
                    TestFinder::KEY_SEARCH => '',
                    TestFinder::KEY_SORT => TestFinder::DEFAULT_SORT,
                    TestFinder::KEY_STATUS => TestFinder::DEFAULT_STATUS,
                ],
            ),
            $this->finder->clearLink(),
        );
    }
}
