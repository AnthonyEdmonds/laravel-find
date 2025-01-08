<?php

namespace AnthonyEdmonds\LaravelFind;

use Carbon\Carbon;

class FindDate
{
    const array THREE_PART_FORMATS = [
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

    const array TWO_PART_FORMATS = [
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

    const array ONE_PART_FORMATS = [
        'd' => '\%d\%',
        'j' => '\%d\%',
        'm' => '\%m\%',
        'n' => '\%m\%',
        'Y' => 'Y-\%',
        'y' => 'Y-\%',
    ];

    static function term(string $term, string $delimiter = '-'): string
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

        return "%$term%";
    }
}
