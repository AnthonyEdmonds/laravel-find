<?php

namespace AnthonyEdmonds\LaravelFind;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinderFormRequest extends FormRequest
{
    public Finder $finder;

    /** @codeCoverageIgnore */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $filters = array_keys(
            $this->finder->listFilters(),
        );

        $sorts = array_keys(
            $this->finder->listSorts(),
        );

        $statuses = array_keys(
            $this->finder->listStatuses(),
        );

        $rules = [];

        $rules[$this->finder::KEY_SEARCH] = [
            'nullable',
            'string',
            'min:1',
        ];

        if (empty($filters) === false) {
            $rules[$this->finder::KEY_FILTER] = [
                'nullable',
                'string',
                Rule::in($filters),
            ];
        }

        if (empty($sorts) === false) {
            $rules[$this->finder::KEY_SORT] = [
                'nullable',
                'string',
                Rule::in($sorts),
            ];
        }

        if (empty($statuses) === false) {
            $rules[$this->finder::KEY_STATUS] = [
                'nullable',
                'string',
                Rule::in($statuses),
            ];
        }

        return $rules;
    }
}
