<?php

namespace App\Http\Requests;

use App\Rules\ReCaptchaV3;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'g-recaptcha-response' => ['required', new ReCaptchaV3('submitContact')],
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
                    ->mixedCase()
                    ->numbers()
            ],
            'alias' => ['required', 'max:100'],
            'email' => ['required', 'email', 'unique:user_infos,email'],
            'id_number' => [
                'string',
                'regex:/[0-9]/',
                'not_regex:/[a-z]/',
                'max:20'
            ],
            'phone_number' => [
                'string',
                'regex:/(0)[0-9]/',
                'not_regex:/[a-z]/',
                'unique:user_infos,phone_number'
            ]
        ];
    }
}
