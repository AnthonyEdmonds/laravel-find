<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Unit\Finder;

use AnthonyEdmonds\LaravelFind\Finder;
use AnthonyEdmonds\LaravelFind\Tests\TestCase;
use AnthonyEdmonds\LaravelFind\Tests\TestFinder;
use Illuminate\Validation\ValidationException;
use Throwable;

class ValidateTest extends TestCase
{
    protected Finder $finder;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testPasses(): void
    {
        $this->expectNotToPerformAssertions();

        request()->query->set(TestFinder::KEY_FILTER, 'author');
        request()->query->set(TestFinder::KEY_SEARCH, '213');
        request()->query->set(TestFinder::KEY_SORT, 'newest');
        request()->query->set(TestFinder::KEY_STATUS, 'all');

        try {
            $this->finder = new TestFinder();
        } catch (Throwable $exception) {
            $this->fail("An exception was thrown when it should not have been: {$exception->getMessage()}");
        }
    }

    public function testFails(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The selected filter is invalid. (and 3 more errors)');

        request()->query->set(TestFinder::KEY_FILTER, 'aosdk');
        request()->query->set(TestFinder::KEY_SEARCH, 213);
        request()->query->set(TestFinder::KEY_SORT, 'aosdk');
        request()->query->set(TestFinder::KEY_STATUS, 'aosdk');

        $this->finder = new TestFinder();
    }
}
