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
        return [
            $this->finder::KEY_FILTER => [
                'nullable',
                'string',
                Rule::in(
                    array_keys(
                        $this->finder->listFilters(),
                    ),
                ),
            ],
            $this->finder::KEY_SEARCH => [
                'nullable',
                'string',
                'min:1',
            ],
            $this->finder::KEY_SORT => [
                'nullable',
                'string',
                Rule::in(
                    array_keys(
                        $this->finder->listSorts(),
                    ),
                ),
            ],
            $this->finder::KEY_STATUS => [
                'nullable',
                'string',
                Rule::in(
                    array_keys(
                        $this->finder->listStatuses(),
                    ),
                ),
            ],
        ];
    }
}
