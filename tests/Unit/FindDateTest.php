<?php

namespace AnthonyEdmonds\LaravelFind\Tests\Unit;

use AnthonyEdmonds\LaravelFind\FindDate;
use AnthonyEdmonds\LaravelFind\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class FindDateTest extends TestCase
{
    #[DataProvider('terms')]
    public function testCreatesTerms(string $term, string|false $expected): void
    {
        $this->assertEquals(
            $expected,
            FindDate::term($term),
        );
    }

    public static function terms(): array
    {
        return [
            // Three Part Formats
            'd-m-Y' => ['term' => '03/08/2012', 'expected' => '2012-08-03%'],
            'd-m-y' => ['term' => '03/08/12', 'expected' => '2012-08-03%'],
            'd-n-Y' => ['term' => '03/8/2012', 'expected' => '2012-08-03%'],
            'd-n-y' => ['term' => '03/8/12', 'expected' => '2012-08-03%'],

            'j-m-Y' => ['term' => '3/08/2012', 'expected' => '2012-08-03%'],
            'j-m-y' => ['term' => '3/08/12', 'expected' => '2012-08-03%'],
            'j-n-Y' => ['term' => '3/8/2012', 'expected' => '2012-08-03%'],
            'j-n-y' => ['term' => '3/8/12', 'expected' => '2012-08-03%'],

            'Y-m-d' => ['term' => '2012/08/03', 'expected' => '2012-08-03%'],
            'Y-m-j' => ['term' => '2012/08/3', 'expected' => '2012-08-03%'],
            'Y-n-d' => ['term' => '2012/8/03', 'expected' => '2012-08-03%'],
            'Y-n-j' => ['term' => '2012/8/3', 'expected' => '2012-08-03%'],

            // Two Part Formats
            'd-m' => ['term' => '03/08', 'expected' => '%08-03%'],
            'd-n' => ['term' => '03/8', 'expected' => '%08-03%'],
            'j-m' => ['term' => '3/08', 'expected' => '%08-03%'],
            'j-n' => ['term' => '3/8', 'expected' => '%08-03%'],

            'm-Y' => ['term' => '08/2012', 'expected' => '2012-08-%'],
            'm-y' => ['term' => '08/69', 'expected' => '2069-08-%'],
            'm-y (conflict)' => ['term' => '08/12', 'expected' => '%12-08%'],
            'n-Y' => ['term' => '8/2012', 'expected' => '2012-08-%'],
            'n-y' => ['term' => '8/32', 'expected' => '2032-08-%'],

            'Y-m' => ['term' => '2012/08', 'expected' => '2012-08-%'],
            'Y-n' => ['term' => '2012/8', 'expected' => '2012-08-%'],

            // One Part Formats
            'd' => ['term' => '03', 'expected' => '%03%'],
            'j' => ['term' => '3', 'expected' => '%03%'],
            'm' => ['term' => '08', 'expected' => '%08%'],
            'n' => ['term' => '8', 'expected' => '%08%'],
            'Y' => ['term' => '2012', 'expected' => '2012-%'],
            'y' => ['term' => '69', 'expected' => '2069-%'],
            'y (conflict)' => ['term' => '12', 'expected' => '%12%'],

            // Invalid Formats
            'invalid' => ['term' => '15468', 'expected' => '%15468%'],
        ];
    }
}
