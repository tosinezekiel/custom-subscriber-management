<?php

namespace App\Http\Requests;

use App\Models\Field;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
        $rules = [
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
        ];

        if ($this->has('fields')) {
            foreach ($this->input('fields') as $index => $field) {
                $fieldId = $field['field_id'];
                $fieldType = Field::find($fieldId)->type ?? null;

                if ($fieldType) {
                    switch ($fieldType) {
                        case 'date':
                            $rules["fields.$index.value"] = 'sometimes|date';
                            break;
                        case 'number':
                            $rules["fields.$index.value"] = 'sometimes|numeric';
                            break;
                        case 'boolean':
                            $rules["fields.$index.value"] = 'sometimes|boolean';
                            break;
                        default:
                            $rules["fields.$index.value"] = 'sometimes|string';
                            break;
                    }
                }
            }
        }

        return $rules;
    }
}
