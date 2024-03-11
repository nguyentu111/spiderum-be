<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class CreateSeries extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => ['required', 'max:100'],
            'discription' => ['sometimes','string'],
            'posts' => ['sometimes','array'],
            'posts.*' => ['exists:posts,id'],
            'thumbnail' => ['sometimes','string']
        ];
    }
   
}
