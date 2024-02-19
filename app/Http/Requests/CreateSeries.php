<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'description' => ['nullabel', 'max:1000'],
            'thumbnail' => ['nullabel', 'max:1000'],
            'is_showed' => ['required', 'boolean'],
        ];
    }
}
