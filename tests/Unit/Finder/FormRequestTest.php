<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Unit\Finder;

use AnthonyEdmonds\LaravelFind\Finder;
use AnthonyEdmonds\LaravelFind\FinderFormRequest;
use AnthonyEdmonds\LaravelFind\Tests\TestCase;
use AnthonyEdmonds\LaravelFind\Tests\TestFinder;

class FormRequestTest extends TestCase
{
    protected Finder $finder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->finder = new TestFinder();
    }

    public function test(): void
    {
        $this->assertEquals(
            FinderFormRequest::class,
            $this->finder->formRequest(),
        );
    }
}
