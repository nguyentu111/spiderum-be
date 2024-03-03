<?php

namespace App\Http\Requests;

use App\Rules\ReCaptchaV3;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPassword extends FormRequest
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
            'password' => [
                'required',
                'confirmed',
                'max:255',
                Password::min(8)
                    ->letters()
            ],
            'email' => ['required', 'email', 'exists:user_infos,email']
        ];
    }
}
