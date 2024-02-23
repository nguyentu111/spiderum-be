<?php

namespace App\Http\Requests;

use App\Enums\SearchNewfeed;
use App\Enums\SortNewFeedEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewfeedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sort' => ['required', Rule::enum(SortNewFeedEnum::class)],
            'page' => ['required', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:5']
        ];
    }
}
