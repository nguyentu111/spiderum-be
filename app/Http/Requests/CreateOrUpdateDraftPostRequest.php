<?php

namespace App\Http\Requests;

use App\Rules\ExistingCategories;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateDraftPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'thumbnail' => ['sometimes', 'string', 'max:1000'],
            'description' => ['sometimes', 'string', 'max:1000','nullable'],
            'name' => ['required', 'string', 'max:255'],
            'content' => ['required', 'json'],
            'id' => 'required'
        ];
    }
}
