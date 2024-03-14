<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetPosts extends FormRequest
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
            'username' => ['sometimes','exists:users,username'],
            'series' => ['sometimes','exists:series,slug'],
            'category' => ['sometimes','exists:categories,slug'],
            'except_cat' => ['sometimes','exists:categories,slug'],

        ];
    }
}
