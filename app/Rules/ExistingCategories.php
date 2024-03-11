<?php 

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Category;
use Closure;

class ExistingCategories implements ValidationRule
{
    // public function passes($attribute, $value)
    // {
    //     // Assuming $value is an array of category IDs
    //     return Category::whereIn('id', $value)->count() === count($value);
    // }

    // public function message()
    // {
    //     return 'One or more categories do not exist.';
    // }
     /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
      if(Category::whereIn('id', $value)->count() !== count($value)) {
        $fail('Một hoặc nhiều danh mục không hợp lệ.');
      }
      return;
    }
}
