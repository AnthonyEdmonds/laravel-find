<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Unit\Finder;

use AnthonyEdmonds\LaravelFind\Finder;
use AnthonyEdmonds\LaravelFind\Tests\TestCase;
use AnthonyEdmonds\LaravelFind\Tests\TestFinder;
use Illuminate\Support\Facades\Session;

class LoadTermTest extends TestCase
{
    protected Finder $finder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->finder = new TestFinder();
    }

    public function testFromRequest(): void
    {
        request()->query->set('test', 'potato');

        $this->assertEquals(
            'potato',
            $this->finder->loadTerm('test', 'carrot'),
        );

        $this->assertEquals(
            'potato',
            Session::get('grim_test'),
        );
    }

    public function testFromSession(): void
    {
        Session::put('grim_test', 'potato');

        $this->assertEquals(
            'potato',
            $this->finder->loadTerm('test', 'carrot'),
        );
    }

    public function testDefaultRequest(): void
    {
        request()->query->set('test', null);

        $this->assertEquals(
            'carrot',
            $this->finder->loadTerm('test', 'carrot'),
        );
    }

    public function testDefaultSession(): void
    {
        Session::put('grim_test', null);

        $this->assertEquals(
            'carrot',
            $this->finder->loadTerm('test', 'carrot'),
        );
    }
}
