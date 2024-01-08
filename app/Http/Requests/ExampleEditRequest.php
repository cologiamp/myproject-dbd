<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rule;

class ExampleEditRequest extends BaseClientRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|max:127',
            'last_name' => 'sometimes|max:127',
            'title' => [
                'sometimes',
                'numeric',
                'integer',
                Rule::in(array_keys(config('enums.client.title'))),
            ],
            'section' => [
                'sometimes',
                'string',
                Rule::in(array_keys(config('section_step_mappings.basic-details')))
            ]
        ];
    }
}
