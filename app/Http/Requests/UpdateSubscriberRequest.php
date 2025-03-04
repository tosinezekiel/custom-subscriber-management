<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubscriberRequest extends FormRequest
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
                'sometimes',
                'email',
                Rule::unique('subscribers', 'email')->ignore($this->subscriber),
                function ($attribute, $value, $fail) {
                    $domain = substr(strrchr($value, "@"), 1);
                    if (!checkdnsrr($domain, 'MX')) {
                        $fail('The email domain must be active.');
                    }
                },
            ],
            'name' => 'sometimes|string|max:255',
            'state' => [
                'sometimes',
                Rule::in(['active', 'unsubscribed', 'junk', 'bounced', 'unconfirmed']),
            ],
            'fields' => 'sometimes|array',
            'fields.*.field_id' => 'required|exists:fields,id',
            'fields.*.value' => 'required|string',
        ];
    }
}
