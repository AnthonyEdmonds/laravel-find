<?php

namespace AnthonyEdmonds\LaravelFind;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FindRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search_term' => 'required|string|min:1|max:191',
            'search_type' => [
                'nullable',
                'string',
                Rule::in(array_keys(Find::types())),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'search_term.required' => 'You must enter a search term',
            'search_term.string' => 'You must enter a search term',
            'search_term.min' => 'Your search term is too short',
            'search_term.max' => 'Your search term is too long',

            'search_type.in' => 'The search type you selected was invalid',
        ];
    }
}
