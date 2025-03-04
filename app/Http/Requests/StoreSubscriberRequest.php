<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubscriberRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
                'unique:subscribers,email',
                function ($attribute, $value, $fail) {
                    $domain = substr(strrchr($value, "@"), 1);
                    if (!checkdnsrr($domain, 'MX')) {
                        $fail('The email domain must be active.');
                    }
                },
            ],
            'name' => 'required|string|max:255',
            'state' => [
                'required',
                Rule::in(['active', 'unsubscribed', 'junk', 'bounced', 'unconfirmed']),
            ],
            'fields' => 'sometimes|array',
            'fields.*.field_id' => 'required|exists:fields,id',
            'fields.*.value' => 'required|string',
        ];
    }
}
