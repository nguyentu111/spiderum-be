<?php

namespace App\Http\Requests;

use App\Enums\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'dob' => [
                'sometimes',
                'date',
            ],
            'alias' => [
                'required',
                'max:100',
            ],
            'description' => [
                'nullable',
                'max:255'
            ],
            'avatar' => [
                'sometimes',
                'string',
            ],
            'wallpaper' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'gender' => [
                Rule::enum(GenderEnum::class)
            ]
        ];
    }
}
