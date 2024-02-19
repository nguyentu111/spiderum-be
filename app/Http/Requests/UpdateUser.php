<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
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
            'id_number' => [
                'sometimes',
                'regex:/[0-9]/',
                'not_regex:/[a-z]/',
                'max:20'
            ],
            'phone_number' => [
                'sometimes',
                'regex:/(0)[0-9]/',
                'not_regex:/[a-z]/',
            ],
            'email' => [
                'sometimes',
                'email',
            ],
            'dob' => [
                'sometimes',
                'date',
            ],
            'alias' => [
                'required',
                'max:100',
            ],
            'description' => [
                'nullabel',
                'max:255'
            ]
        ];
    }
}
