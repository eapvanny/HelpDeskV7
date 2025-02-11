<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExcelFileValidation implements ValidationRule
{
    protected $maxSize;

    public function __construct($maxSize)
    {
        $this->maxSize = $maxSize;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        $extension = strtolower($value->getClientOriginalExtension());
        if ($extension !== 'xlsx') {
            $fail("The :attribute must be a valid Excel file (.xlsx).");
        }

        // Check if the file size is less than or equal to the specified limit
        if ($value->getSize() > $this->maxSize * 1024 * 1024) {
            $fail("The :attribute size must be maximum size of {$this->maxSize} MB.");
        } // Convert MB to KB
    }

}
