<?php

namespace AnthonyEdmonds\LaravelFind;

use Carbon\Carbon;

class FindDate
{
    public const array THREE_PART_FORMATS = [
        'd-m-Y' => 'Y-m-d\%',
        'd-m-y' => 'Y-m-d\%',
        'd-n-Y' => 'Y-m-d\%',
        'd-n-y' => 'Y-m-d\%',

        'j-m-Y' => 'Y-m-d\%',
        'j-m-y' => 'Y-m-d\%',
        'j-n-Y' => 'Y-m-d\%',
        'j-n-y' => 'Y-m-d\%',

        'Y-m-d' => 'Y-m-d\%',
        'Y-m-j' => 'Y-m-d\%',
        'Y-n-d' => 'Y-m-d\%',
        'Y-n-j' => 'Y-m-d\%',
    ];

    public const array TWO_PART_FORMATS = [
        'd-m' => '\%m-d\%',
        'd-n' => '\%m-d\%',
        'j-m' => '\%m-d\%',
        'j-n' => '\%m-d\%',

        'm-Y' => 'Y-m-\%',
        'm-y' => 'Y-m-\%',
        'n-Y' => 'Y-m-\%',
        'n-y' => 'Y-m-\%',

        'Y-m' => 'Y-m-\%',
        'Y-n' => 'Y-m-\%',
    ];

    public const array ONE_PART_FORMATS = [
        // 'd' => '\%-d\%', Causes too many conflicts with other search terms, such as IDs
        // 'j' => '\%-d\%', Causes too many conflicts with other search terms, such as IDs
        // 'm' => '\%-m\%', Causes too many conflicts with other search terms, such as IDs
        // 'n' => '\%-m\%', Causes too many conflicts with other search terms, such as IDs
        'Y' => 'Y-\%',
        // 'y' => 'Y-\%', Causes too many conflicts with other search terms, such as IDs
    ];

    public static function term(string $term, string $delimiter = '-'): string|false
    {
        $term = preg_replace('/\D/', $delimiter, $term);

        $formats = match (substr_count($term, $delimiter)) {
            2 => self::THREE_PART_FORMATS,
            1 => self::TWO_PART_FORMATS,
            0 => self::ONE_PART_FORMATS,
            default => [],
        };

        foreach ($formats as $inputFormat => $outputFormat) {
            if (Carbon::canBeCreatedFromFormat($term, $inputFormat) === true) {
                return Carbon::createFromFormat($inputFormat, $term)
                    ->format($outputFormat);
            }
        }

        return false;
    }
}
