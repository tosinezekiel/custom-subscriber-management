<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFieldRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:fields,title',
            'type' => [
                'required',
                Rule::in(['date', 'number', 'string', 'boolean']),
            ],
        ];
    }


    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.unique' => 'A field with this title already exists.',
            'type.in' => 'The field type must be one of: date, number, string, boolean.',
        ];
    }
}
