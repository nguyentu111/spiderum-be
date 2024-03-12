<?php

namespace App\Http\Requests;

use App\Rules\ExistingCategories;
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
            'thumbnail' => ['required', 'string', 'max:1000'],
            'description' => ['sometimes', 'string', 'max:1000'],
            'name' => ['required', 'string', 'max:255'],
            'content' => ['required', 'json'],
            'is_shown' => ['sometimes', 'boolean'],
            'series' => ['sometimes','exists:series,id'],
            'categories' => ['required','array', new ExistingCategories],
            'categories.*' => ['required', 'string'],
        ];
    }
}
