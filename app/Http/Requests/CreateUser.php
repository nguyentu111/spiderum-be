<?php

namespace App\Http\Requests;

use App\Rules\ReCaptchaV3;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateUser extends FormRequest
{
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
            'username' => [
                'required',
                'max:255',
                'unique:users,username',
                'required'],
            'password' => [
                'required',
                'confirmed',
                'max:255',
                Password::min(8)
                    ->letters()
            ],
            'alias' => ['required', 'max:100'],
            'email' => ['required', 'email', 'unique:user_infos,email'],
            'id_number' => [
                'nullable',
                'regex:/[0-9]/',
                'not_regex:/[a-z]/',
                'max:20'
            ],
            'phone_number' => [
                'nullable',
                'regex:/(0)[0-9]/',
                'not_regex:/[a-z]/',
            ],
            'avatar_url' => [
                'nullable'
            ]
        ];
    }
}
