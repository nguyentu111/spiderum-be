<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'thumnail' => ['required', 'string', 'max:1000'],
            'name' => ['required', 'string', 'max:255'],
            'content' => ['required', 'json'],
            'is_shown' => ['sometimes', 'boolean'],
        ];
    }
}
